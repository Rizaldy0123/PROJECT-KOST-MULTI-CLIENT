<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Room;
use App\Traits\ResolvesPenghuni;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenghuniSewaController extends Controller
{
    use ResolvesPenghuni;

    // FORM SEWA (Create)
    public function create(Request $request)
    {
        $clientUser = $this->currentPenghuni();

        if ($this->hasActiveContract($clientUser->id)) {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Anda masih memiliki sewa aktif. Batalkan sewa tersebut terlebih dahulu jika ingin menyewa kamar lain.');
        }

        $room = Room::where('status', 'tersedia')
            ->whereHas('property', fn ($q) => $q->where('status', 'aktif'))
            ->with(['property', 'roomType'])
            ->find($request->query('room'));

        if (! $room) {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Kamar tidak ditemukan atau sudah tidak tersedia.');
        }

        return view('penghuni.sewa.create', [
            'user' => Auth::user(),
            'room' => $room,
        ]);
    }

    // SIMPAN SEWA (Create)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'      => 'required|integer',
            'start_date'   => 'required|date|after_or_equal:today',
            'durasi_bulan' => 'required|integer|min:1|max:24',
        ]);

        $clientUser = $this->currentPenghuni();

        if ($this->hasActiveContract($clientUser->id)) {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Anda masih memiliki sewa aktif.');
        }

        $start = Carbon::parse($validated['start_date']);
        $end   = $start->copy()->addMonths((int) $validated['durasi_bulan']);

        $result = DB::transaction(function () use ($validated, $clientUser, $start, $end) {
            // Kunci baris kamar supaya tidak terjadi double booking
            $room = Room::whereHas('property', fn ($q) => $q->where('status', 'aktif'))
                ->where('status', 'tersedia')
                ->lockForUpdate()
                ->find($validated['room_id']);

            if (! $room) {
                return false;
            }

            Contract::create([
                'room_id'        => $room->id,
                'client_id'      => $room->property->client_id,
                'client_user_id' => $clientUser->id,
                'start_date'     => $start->toDateString(),
                'end_date'       => $end->toDateString(),
                'status'         => 'aktif',
            ]);

            $room->update(['status' => 'terisi']);

            return true;
        });

        if (! $result) {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Maaf, kamar tersebut baru saja disewa orang lain atau sudah tidak tersedia.');
        }

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Kamar berhasil disewa!');
    }

    // FORM UBAH SEWA (Update)
    public function edit(Contract $contract)
    {
        $this->authorizeContract($contract);

        if ($contract->status !== 'aktif') {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Hanya sewa yang masih aktif yang dapat diubah.');
        }

        $contract->load(['room.property', 'room.roomType']);

        $durasi = max(1, (int) round(
            Carbon::parse($contract->start_date)->floatDiffInMonths(Carbon::parse($contract->end_date))
        ));

        return view('penghuni.sewa.edit', [
            'user'     => Auth::user(),
            'contract' => $contract,
            'durasi'   => $durasi,
        ]);
    }

    // SIMPAN PERUBAHAN SEWA (Update)
    public function update(Request $request, Contract $contract)
    {
        $this->authorizeContract($contract);

        if ($contract->status !== 'aktif') {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Hanya sewa yang masih aktif yang dapat diubah.');
        }

        $validated = $request->validate([
            'start_date'   => 'required|date',
            'durasi_bulan' => 'required|integer|min:1|max:24',
        ]);

        $start = Carbon::parse($validated['start_date']);
        $end   = $start->copy()->addMonths((int) $validated['durasi_bulan']);

        $contract->update([
            'start_date' => $start->toDateString(),
            'end_date'   => $end->toDateString(),
        ]);

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Data sewa berhasil diperbarui!');
    }

    // BATALKAN SEWA (Delete)
    public function destroy(Contract $contract)
    {
        $this->authorizeContract($contract);

        if ($contract->status !== 'aktif') {
            return redirect()->route('penghuni.dashboard')
                ->with('error', 'Sewa ini sudah tidak aktif.');
        }

        DB::transaction(function () use ($contract) {
            $contract->update(['status' => 'dibatalkan']);
            Room::where('id', $contract->room_id)->update(['status' => 'tersedia']);
        });

        return redirect()->route('penghuni.dashboard')
            ->with('success', 'Sewa berhasil dibatalkan.');
    }

    private function hasActiveContract(int $clientUserId): bool
    {
        return Contract::where('client_user_id', $clientUserId)
            ->where('status', 'aktif')
            ->exists();
    }

    // Pastikan kontrak benar-benar milik penghuni yang login
    private function authorizeContract(Contract $contract): void
    {
        $clientUser = $this->currentPenghuni();

        abort_unless($contract->client_user_id === $clientUser->id, 403);
    }
}

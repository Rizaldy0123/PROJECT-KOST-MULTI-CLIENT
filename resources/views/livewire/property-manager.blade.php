<?php

use Livewire\Component;
use App\Models\Property;

new class extends Component
{
    public function render()
    {
        return view('livewire.property-manager', [
            'properties' => Property::all()
        ]);
    }
};
?>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md border border-gray-100">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Properti Kost</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data seluruh lokasi kost dengan mudah.</p>
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs tracking-wider border-b border-gray-200">
                    <th class="py-3 px-4 font-semibold">Nama Properti</th>
                    <th class="py-3 px-4 font-semibold">Alamat</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-600">
                @forelse($properties as $property)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-medium text-gray-900">{{ $property->name }}</td>
                        <td class="py-3 px-4">{{ $property->address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="py-8 text-center text-gray-400 italic">
                            Belum ada data properti kost.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
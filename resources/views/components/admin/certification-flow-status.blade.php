@props(['apl01', 'flowStatus' => null, 'compact' => false])

@php
    // Get flow status if not provided
    if (!$flowStatus && $apl01) {
        $flowService = app(\App\Services\CertificationFlowService::class);
        $flowStatus = $flowService->getFlowStatus($apl01);
    }

    $stages = [
        [
            'key' => 'apl01_approved',
            'icon' => 'description',
            'label' => 'APL-01',
            'sublabel' => 'Disetujui',
            'completed' => $flowStatus['apl01']['approved'] ?? false,
        ],
        [
            'key' => 'apl02_generated',
            'icon' => 'assignment',
            'label' => 'APL-02',
            'sublabel' => 'Asesmen Mandiri',
            'completed' => $flowStatus['apl02']['all_complete'] ?? false,
            'in_progress' => ($flowStatus['apl02']['generated'] ?? false) && !($flowStatus['apl02']['all_complete'] ?? false),
            'progress' => $flowStatus['apl02']['progress_percentage'] ?? 0,
        ],
        [
            'key' => 'assessment',
            'icon' => 'event',
            'label' => 'Asesmen',
            'sublabel' => 'Penilaian',
            'completed' => $flowStatus['assessment']['approved'] ?? false,
            'in_progress' => ($flowStatus['assessment']['exists'] ?? false) && !($flowStatus['assessment']['approved'] ?? false),
        ],
        [
            'key' => 'certificate',
            'icon' => 'workspace_premium',
            'label' => 'Sertifikat',
            'sublabel' => 'Diterbitkan',
            'completed' => $flowStatus['certificate']['exists'] ?? false,
        ],
    ];
@endphp

@if($compact)
{{-- Compact version for table cells --}}
<div class="flex items-center space-x-1">
    @foreach($stages as $index => $stage)
        @php
            $bgClass = 'bg-gray-200 text-gray-400';
            $icon = 'remove';
            if ($stage['completed']) {
                $bgClass = 'bg-green-500 text-white';
                $icon = 'check';
            } elseif ($stage['in_progress'] ?? false) {
                $bgClass = 'bg-yellow-500 text-white';
                $icon = 'pending';
            }
        @endphp
        <div class="flex flex-col items-center">
            <div class="w-6 h-6 rounded-full flex items-center justify-center {{ $bgClass }}">
                <span class="material-symbols-outlined text-xs">{{ $icon }}</span>
            </div>
        </div>
        @if($index < count($stages) - 1)
            <div class="w-3 h-0.5 {{ $stage['completed'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
        @endif
    @endforeach
</div>
@else
{{-- Full version with labels --}}
<div class="flex items-center justify-between">
    @foreach($stages as $index => $stage)
        @php
            $bgClass = 'bg-gray-200 text-gray-400';
            $icon = 'remove';
            $statusText = '';
            $statusColor = 'text-gray-400';

            if ($stage['completed']) {
                $bgClass = 'bg-green-500 text-white';
                $icon = 'check';
                $statusText = 'Selesai';
                $statusColor = 'text-green-600';
            } elseif ($stage['in_progress'] ?? false) {
                $bgClass = 'bg-yellow-500 text-white';
                $icon = 'pending';
                $statusText = 'Dalam Proses';
                $statusColor = 'text-yellow-600';

                // Show progress for APL-02
                if ($stage['key'] === 'apl02_generated' && isset($stage['progress'])) {
                    $statusText = $stage['progress'] . '%';
                }
            }
        @endphp

        <div class="flex flex-col items-center flex-1">
            <div class="w-12 h-12 rounded-full flex items-center justify-center {{ $bgClass }} relative z-10">
                <span class="material-symbols-outlined">{{ $stage['icon'] }}</span>
            </div>
            <span class="text-sm font-medium text-gray-700 mt-2">{{ $stage['label'] }}</span>
            <span class="text-xs text-gray-500">{{ $stage['sublabel'] }}</span>
            @if($statusText)
                <span class="text-xs {{ $statusColor }} mt-1">{{ $statusText }}</span>
            @endif
        </div>

        @if($index < count($stages) - 1)
            <div class="flex-1 h-1 {{ $stage['completed'] ? 'bg-green-500' : 'bg-gray-200' }} -mt-16"></div>
        @endif
    @endforeach
</div>
@endif

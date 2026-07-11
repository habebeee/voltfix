@php
$config = [
    'PENDING'            => ['label' => 'Menunggu Konfirmasi', 'class' => 'bg-yellow-100 text-yellow-800'],
    'REJECTED'           => ['label' => 'Ditolak',             'class' => 'bg-red-100 text-red-800'],
    'WAITING_ASSIGNMENT' => ['label' => 'Mencari Teknisi',     'class' => 'bg-blue-100 text-blue-800'],
    'ASSIGNED'           => ['label' => 'Teknisi Ditugaskan',  'class' => 'bg-indigo-100 text-indigo-800'],
    'ON_THE_WAY'         => ['label' => 'Teknisi Dalam Perjalanan', 'class' => 'bg-purple-100 text-purple-800'],
    'DIAGNOSIS'          => ['label' => 'Diagnosa',            'class' => 'bg-cyan-100 text-cyan-800'],
    'WAITING_PART'       => ['label' => 'Menunggu Spare Part', 'class' => 'bg-orange-100 text-orange-800'],
    'REPAIR'             => ['label' => 'Perbaikan',           'class' => 'bg-violet-100 text-violet-800'],
    'COMPLETED'          => ['label' => 'Selesai',             'class' => 'bg-green-100 text-green-800'],
    'CLOSED'             => ['label' => 'Ditutup',             'class' => 'bg-gray-100 text-gray-600'],
];
$badge = $config[$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-600'];
@endphp
<span class="badge-status {{ $badge['class'] }}">{{ $badge['label'] }}</span>

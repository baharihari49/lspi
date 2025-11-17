@extends('layouts.app')

@section('title', 'Profil - LSP Pustaka Ilmiah Elektronik')

@php
    $active = 'profile';
@endphp

@section('content')
    <div class="px-4 md:px-10 lg:px-20 xl:px-40 flex flex-1 justify-center py-5">
        <div class="flex flex-col max-w-[960px] flex-1">
            <div class="flex flex-col gap-8 md:gap-12 py-8 md:py-12">
                <div class="flex flex-wrap justify-between gap-3 p-4">
                    <div class="flex w-full flex-col gap-3 text-center">
                        <p class="text-gray-900 text-4xl md:text-5xl font-black leading-tight tracking-[-0.033em]">Profil LSP Pustaka Ilmiah Elektronik</p>
                        <p class="text-blue-800 text-base font-normal leading-normal">LSP P3 Asosiasi Industri/Profesi</p>
                    </div>
                </div>
                <div class="px-4">
                    <div class="w-full h-64 md:h-96 overflow-hidden rounded-xl border border-gray-200">
                        <img alt="Professional meeting in a modern office" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCYyDev19jmiXPhDFiBKBZRNgrI_CRMdoRbtJDeT88WVH7SyV7rw3Xtz3v8-8A0naV2H6BdEKbRYHAxQMglTCU6n87RzZfFR7FHaTfgH8u9EuEU3M8ty1zyeppXNHe2ZniXMrTCvZJeBIBrFutSTuS-HzRrfKHuwMj6J4V7orQh9c7qTdqGCdndPeI4YLafVvZVNQoRNu-HZ2UR5GaSpxr4_arm0lsavaT_zhZKN-3Oy3e9fehl3a5GBfX_VxKM8e_YF0uSc8REWLEl"/>
                    </div>
                </div>
                <div class="px-4 pt-8 md:pt-4">
                    <p class="text-gray-800 text-base font-normal leading-relaxed text-center max-w-4xl mx-auto">LSP-PIE adalah lembaga sertifikasi resmi yang berfokus pada peningkatan kompetensi sumber daya manusia di bidang pengelolaan pustaka ilmiah, khususnya jurnal ilmiah elektronik. Kami hadir untuk memastikan para profesional di bidang pengelolaan jurnal seperti editor, reviewer, manajer jurnal, dan teknisi penerbitan elektronik memiliki standar kompetensi yang sesuai dengan kebutuhan industri dan perkembangan teknologi informasi. Sertifikasi yang kami selenggarakan mengacu pada Standar Kompetensi Kerja Nasional Indonesia (SKKNI) dan dirancang untuk mendukung kualitas publikasi ilmiah Indonesia yang berdaya saing global.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
                    <div class="flex flex-col gap-6 md:col-span-2">
                        <div class="flex flex-col sm:flex-row gap-6 rounded-xl border border-gray-200 bg-white p-6">
                            <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                <span class="material-symbols-outlined">satellite</span>
                            </div>
                            <div class="flex flex-col gap-2 text-center sm:text-left">
                                <h2 class="text-gray-900 text-xl font-bold leading-tight">Visi</h2>
                                <p class="text-gray-600 text-base font-normal leading-relaxed">Menjadi Lembaga Sertifikasi Profesi yang Unggul, Profesional, dan Kompeten dalam Bidang Perpustakaan dan Terbitan Ilmiah dalam skala Nasional maupun Internasional.</p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-6 rounded-xl border border-gray-200 bg-white p-6">
                            <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                                <span class="material-symbols-outlined">flutter_dash</span>
                            </div>
                            <div class="flex flex-col gap-2 text-center sm:text-left">
                                <h2 class="text-gray-900 text-xl font-bold leading-tight">Misi</h2>
                                <ul class="text-gray-600 text-base font-normal leading-relaxed list-disc pl-5 space-y-2 text-left">
                                    <li>Memberikan Pelayanan Uji Sertifikasi Kompetensi yang mengutamakan mutu dan kepuasan pelanggan.</li>
                                    <li>Memberikan jaminan bahwa proses Uji Sertifikasi dilaksanakan dengan kejujuran, teliti, tepat, akurat, effisien, dan efektif.</li>
                                    <li>Mengembangkan tersedianya tenaga kerja yang kompeten, profesional dan kompetitif di bidang Perpustakaan dan Terbitan Ilmiah.</li>
                                    <li>Mengembangkan Sarana dan Prasarana standar Kompetensi Kerja di bidang Perpustakaan dan Terbitan Ilmiah secara konsisten dan berkesinambungan sesuai dengan perkembangan dan kebutuhan industri ataupun profesi.</li>
                                    <li>Mengembangkan tata kelola tenaga Asesor kompetensi yang berkualifikasi dan bersertifikat sesuai dengan ruang lingkup sertifikasi LSP Pustaka Ilmiah Elektronik.</li>
                                    <li>Mengembangkan perangkat asesmen.</li>
                                    <li>Mengembangkan sistem pendukung berbasis teknologi dan informasi.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6 rounded-xl border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                            <span class="material-symbols-outlined">trending_up</span>
                        </div>
                        <div class="flex flex-col gap-2 text-center">
                            <h2 class="text-gray-900 text-xl font-bold leading-tight">Target</h2>
                            <p class="text-gray-600 text-base font-normal leading-relaxed">100 asesi per tahun</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-6 rounded-xl border border-gray-200 bg-white p-6">
                        <div class="flex items-center justify-center size-12 rounded-full bg-blue-100 text-blue-900 flex-shrink-0">
                            <span class="material-symbols-outlined">emoji_events</span>
                        </div>
                        <div class="flex flex-col gap-2 text-center">
                            <h2 class="text-gray-900 text-xl font-bold leading-tight">Tujuan</h2>
                            <p class="text-gray-600 text-base font-normal leading-relaxed">Menciptakan sumber daya manusia yang kompeten dan memiliki karakteristik unggul serta profesional di dunia terbitan berkala ilmiah.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

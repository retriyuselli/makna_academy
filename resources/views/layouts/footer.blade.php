<!-- Footer -->
<style>
.footer-description,
.footer-description p,
.footer-description div {
    font-size: 0.875rem !important;
    line-height: 1.25rem !important;
}
</style>
<footer class="bg-gray-800 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                @if($footerCompany)
                    <h3 class="text-lg font-bold mb-4">Makna Academy</h3>
                    <div class="text-gray-300 text-sm leading-relaxed footer-description" style="font-size: 0.875rem !important; line-height: 1.25rem !important;">{!! str($footerCompany->description)->sanitizeHtml() !!}</div>
                @else
                    <h3 class="text-lg font-bold mb-4">Makna Academy</h3>
                    <div class="text-gray-300 text-sm leading-relaxed footer-description" style="font-size: 0.875rem !important; line-height: 1.25rem !important;">Platform event terbaik untuk mengembangkan skill dan networking Anda.</div>
                @endif
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="text-gray-300 text-sm hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('events.index') }}" class="text-gray-300 text-sm hover:text-white">Event</a></li>
                    <li><a href="{{ route('about') }}" class="text-gray-300 text-sm hover:text-white">Tentang</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                <ul class="space-y-2 text-gray-300">
                    @if($footerCompany)
                        <li class="text-sm">
                            <i class="fas fa-envelope mr-2"></i>
                            <a href="mailto:{{ $footerCompany->email }}" class="hover:text-white transition-colors">
                                {{ $footerCompany->email }}
                            </a>
                        </li>
                        <li class="text-sm">
                            <i class="fas fa-phone mr-2"></i>
                            <a href="tel:{{ $footerCompany->phone }}" class="hover:text-white transition-colors">
                                {{ $footerCompany->phone }}
                            </a>
                        </li>
                        <li class="text-sm">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            {{ $footerCompany->address ? $footerCompany->address . ', ' : '' }}{{ $footerCompany->city ?? 'Jakarta' }}, {{ $footerCompany->country ?? 'Indonesia' }}
                        </li>
                    @else
                        {{-- Fallback jika tidak ada data company --}}
                        <li class="text-sm"><i class="fas fa-envelope mr-2"></i>info@maknaacademy.com</li>
                        <li class="text-sm"><i class="fas fa-phone mr-2"></i>+62 123 456 789</li>
                        <li class="text-sm"><i class="fas fa-map-marker-alt mr-2"></i>Jakarta, Indonesia</li>
                    @endif
                </ul>
            </div>
            <div>
                <h4 class="text-lg font-semibold mb-4">Follow Us</h4>
                <div class="flex space-x-4">
                    @if($footerCompany && $footerCompany->social_media)
                        @foreach($footerCompany->social_media as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" class="text-gray-300 hover:text-white transition-colors" title="{{ ucfirst($platform) }}">
                                    @switch(strtolower($platform))
                                        @case('instagram')
                                            <i class="fab fa-instagram text-xl"></i>
                                    @endswitch
                                </a>
                            @endif
                        @endforeach
                    @else
                        {{-- Fallback default social media --}}
                        <a href="https://www.instagram.com/makna_academy.id?igsh=MXU0YnQzdG5iYnE5" target="_blank" class="text-gray-300 hover:text-white transition-colors" title="Instagram">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="border-t border-gray-700 mt-8 pt-8 text-center">
            <p class="text-gray-300 text-xs">
                &copy; {{ date('Y') }} 
                @if($footerCompany)
                    {{ $footerCompany->name }}{{ $footerCompany->legal_name ? ' by ' . $footerCompany->legal_name : '' }}
                @else
                    Makna Academy by PT. Makna Kreatif Indonesia
                @endif
                . All Rights Reserved.
            </p>
        </div>
    </div>
</footer>
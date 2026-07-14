{{-- Floating support button --}}
<button class="jtc-fab" aria-label="Call support" @click="openSupport()">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="20" height="20"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1 .37 1.94.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.87.35 1.81.59 2.81.72A2 2 0 0 1 22 16.92z"></path></svg>
    <span>Call support</span>
</button>

@php
    $supportPhone     = $site['phone'] ?? '8801329732724';
    $supportWhatsapp  = $site['whatsapp'] ?? '8801329732724';
    $supportMessenger = $site['messenger'] ?? 'https://m.me/juweltradecorporation';

    $supportItems = collect([
        [
            'key' => 'call',
            'icon' => 'phone',
            'label' => 'Call us',
            'title' => 'Call us',
            'desc' => 'Speak with our team directly.',
            'value' => '+' . $supportPhone,
            'action_label' => 'Call +' . $supportPhone,
            'href' => 'tel:+' . $supportPhone,
            'target' => null,
        ],
        !empty($site['phone_second']) ? [
            'key' => 'call2',
            'icon' => 'phone',
            'label' => 'Call us (alt)',
            'title' => 'Call us (alt)',
            'desc' => 'Alternate line if the first is busy.',
            'value' => '+' . $site['phone_second'],
            'action_label' => 'Call +' . $site['phone_second'],
            'href' => 'tel:+' . $site['phone_second'],
            'target' => null,
        ] : null,
        [
            'key' => 'whatsapp',
            'icon' => 'wa',
            'label' => 'WhatsApp',
            'title' => 'WhatsApp',
            'desc' => 'Chat with us instantly.',
            'value' => '+' . $supportWhatsapp,
            'action_label' => 'Open WhatsApp',
            'href' => 'https://wa.me/' . $supportWhatsapp,
            'target' => '_blank',
        ],
        [
            'key' => 'messenger',
            'icon' => 'msg',
            'label' => 'Messenger',
            'title' => 'Messenger',
            'desc' => 'Message us on Facebook.',
            'value' => null,
            'action_label' => 'Open Messenger',
            'href' => $supportMessenger,
            'target' => '_blank',
        ],
        !empty($site['email']) ? [
            'key' => 'email',
            'icon' => 'mail',
            'label' => 'Email',
            'title' => 'Email',
            'desc' => 'Send us a message anytime.',
            'value' => $site['email'],
            'action_label' => 'Email us',
            'href' => 'mailto:' . $site['email'],
            'target' => null,
        ] : null,
    ])->filter()->values();

    $supportSocials = collect([
        !empty($site['facebook'])  ? ['key' => 'fb', 'label' => 'Facebook',  'href' => $site['facebook']]  : null,
        !empty($site['instagram']) ? ['key' => 'ig', 'label' => 'Instagram', 'href' => $site['instagram']] : null,
        !empty($site['youtube'])   ? ['key' => 'yt', 'label' => 'YouTube',   'href' => $site['youtube']]   : null,
        !empty($site['tiktok'])    ? ['key' => 'tt', 'label' => 'TikTok',    'href' => $site['tiktok']]    : null,
    ])->filter()->values();
@endphp

<div class="jtc-modal-scrim" :class="supportOpen && 'is-open'" @click="supportOpen = false" x-cloak>
    <div class="jtc-modal jtc-modal--support" @click.stop x-data="{ activeSupport: '{{ $supportItems->first()['key'] ?? '' }}' }">
        <div class="jtc-modal__head jtc-modal__head--brand">
            <button class="jtc-modal__close jtc-modal__close--light" aria-label="Close" @click="supportOpen = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
            <h3>We're here to help</h3>
            <p>Reach Juwel Trade Corporation any way you like.</p>
        </div>

        <div class="jtc-support">
            {{-- Left: nav --}}
            <div class="jtc-support__nav">
                @foreach ($supportItems as $item)
                    <button type="button" class="jtc-support__navitem" :class="activeSupport === '{{ $item['key'] }}' && 'is-active'" @click="activeSupport = '{{ $item['key'] }}'">
                        <span class="jtc-support__navicon jtc-support__navicon--{{ $item['icon'] }}">
                            @switch($item['icon'])
                                @case('phone')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1 .37 1.94.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.87.35 1.81.59 2.81.72A2 2 0 0 1 22 16.92z"></path></svg>
                                    @break
                                @case('wa')
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="19" height="19"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"></path></svg>
                                    @break
                                @case('msg')
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="19" height="19"><path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.9 1.19 5.4 3.14 7.13.16.14.26.35.27.57l.05 1.78c.02.57.6.94 1.12.71l1.99-.88c.17-.07.36-.09.54-.04 1 .27 2.06.42 3.14.42 5.64 0 10-4.13 10-9.7C22 6.13 17.64 2 12 2zm6 7.46-2.93 4.66c-.47.74-1.47.93-2.18.4l-2.33-1.75a.6.6 0 0 0-.72 0l-3.15 2.39c-.42.32-.97-.18-.69-.63l2.93-4.66c.47-.74 1.47-.93 2.18-.4l2.33 1.75c.21.16.51.16.72 0l3.15-2.39c.42-.32.97.18.69.63z"></path></svg>
                                    @break
                                @case('mail')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M3 7l9 6 9-6"></path><rect x="3" y="5" width="18" height="14" rx="2"></rect></svg>
                            @endswitch
                        </span>
                        <span class="jtc-support__navlabel">{{ $item['label'] }}</span>
                    </button>
                @endforeach

                @if ($supportSocials->isNotEmpty())
                    <button type="button" class="jtc-support__navitem" :class="activeSupport === 'social' && 'is-active'" @click="activeSupport = 'social'">
                        <span class="jtc-support__navicon jtc-support__navicon--social">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.6" y1="10.5" x2="15.4" y2="6.5"></line><line x1="8.6" y1="13.5" x2="15.4" y2="17.5"></line></svg>
                        </span>
                        <span class="jtc-support__navlabel">Follow us</span>
                    </button>
                @endif
            </div>

            {{-- Right: content --}}
            <div class="jtc-support__content">
                @foreach ($supportItems as $item)
                    <div class="jtc-support__pane" x-show="activeSupport === '{{ $item['key'] }}'" x-cloak>
                        <span class="jtc-support__paneicon jtc-support__paneicon--{{ $item['icon'] }}">
                            @switch($item['icon'])
                                @case('phone')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13 1 .37 1.94.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.87.35 1.81.59 2.81.72A2 2 0 0 1 22 16.92z"></path></svg>
                                    @break
                                @case('wa')
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="27" height="27"><path d="M12.04 2C6.58 2 2.13 6.45 2.13 11.91c0 1.75.46 3.45 1.32 4.95L2 22l5.25-1.38a9.9 9.9 0 0 0 4.79 1.22h.01c5.46 0 9.9-4.45 9.9-9.91 0-2.65-1.03-5.14-2.9-7.01A9.82 9.82 0 0 0 12.04 2zm0 18.15h-.01a8.2 8.2 0 0 1-4.19-1.15l-.3-.18-3.11.82.83-3.04-.2-.31a8.19 8.19 0 0 1-1.26-4.38c0-4.54 3.7-8.24 8.24-8.24 2.2 0 4.27.86 5.83 2.42a8.19 8.19 0 0 1 2.41 5.83c0 4.54-3.7 8.24-8.24 8.24zm4.52-6.16c-.25-.12-1.47-.72-1.69-.81-.23-.08-.39-.12-.56.12-.16.25-.64.81-.79.98-.14.16-.29.18-.54.06-.25-.12-1.05-.39-1.99-1.23-.74-.66-1.23-1.47-1.38-1.72-.14-.25-.01-.38.11-.5.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.34-.76-1.84-.2-.48-.4-.42-.56-.43h-.48c-.16 0-.43.06-.66.31-.23.25-.86.85-.86 2.07 0 1.22.89 2.4 1.01 2.56.12.16 1.75 2.67 4.23 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.47-.6 1.68-1.18.21-.58.21-1.07.14-1.18-.06-.11-.22-.17-.47-.29z"></path></svg>
                                    @break
                                @case('msg')
                                    <svg viewBox="0 0 24 24" fill="currentColor" width="27" height="27"><path d="M12 2C6.36 2 2 6.13 2 11.7c0 2.9 1.19 5.4 3.14 7.13.16.14.26.35.27.57l.05 1.78c.02.57.6.94 1.12.71l1.99-.88c.17-.07.36-.09.54-.04 1 .27 2.06.42 3.14.42 5.64 0 10-4.13 10-9.7C22 6.13 17.64 2 12 2zm6 7.46-2.93 4.66c-.47.74-1.47.93-2.18.4l-2.33-1.75a.6.6 0 0 0-.72 0l-3.15 2.39c-.42.32-.97-.18-.69-.63l2.93-4.66c.47-.74 1.47-.93 2.18-.4l2.33 1.75c.21.16.51.16.72 0l3.15-2.39c.42-.32.97.18.69.63z"></path></svg>
                                    @break
                                @case('mail')
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" width="26" height="26"><path d="M3 7l9 6 9-6"></path><rect x="3" y="5" width="18" height="14" rx="2"></rect></svg>
                            @endswitch
                        </span>
                        <h4 class="jtc-support__panetitle">{{ $item['title'] }}</h4>
                        <p class="jtc-support__panedesc">{{ $item['desc'] }}</p>
                        @if ($item['value'])
                            <div class="jtc-support__panevalue">{{ $item['value'] }}</div>
                        @endif
                        <a href="{{ $item['href'] }}" @if ($item['target']) target="{{ $item['target'] }}" rel="noopener" @endif class="jtc-btn jtc-btn--primary jtc-support__paneaction">
                            {{ $item['action_label'] }}
                        </a>
                    </div>
                @endforeach

                @if ($supportSocials->isNotEmpty())
                    <div class="jtc-support__pane" x-show="activeSupport === 'social'" x-cloak>
                        <span class="jtc-support__paneicon jtc-support__paneicon--social">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="26" height="26"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.6" y1="10.5" x2="15.4" y2="6.5"></line><line x1="8.6" y1="13.5" x2="15.4" y2="17.5"></line></svg>
                        </span>
                        <h4 class="jtc-support__panetitle">Follow us</h4>
                        <p class="jtc-support__panedesc">Stay up to date with our latest news and offers.</p>
                        <div class="jtc-support__socials">
                            @foreach ($supportSocials as $social)
                                <a href="{{ $social['href'] }}" target="_blank" rel="noopener" class="jtc-support__social jtc-support__social--{{ $social['key'] }}" aria-label="{{ $social['label'] }}">
                                    @switch($social['key'])
                                        @case('fb')
                                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M24 12a12 12 0 1 0-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.6 4.5-4.6 1.3 0 2.7.2 2.7.2v2.9h-1.5c-1.5 0-2 .9-2 1.9V12h3.3l-.5 3.5h-2.8v8.4A12 12 0 0 0 24 12z"></path></svg>
                                            @break
                                        @case('ig')
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><rect x="2" y="2" width="20" height="20" rx="5"></rect><circle cx="12" cy="12" r="4"></circle><line x1="17.5" y1="6.5" x2="17.5" y2="6.5"></line></svg>
                                            @break
                                        @case('yt')
                                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M23 12s0-3.5-.46-5.17a2.78 2.78 0 0 0-1.94-1.96C18.88 4.4 12 4.4 12 4.4s-6.88 0-8.6.47A2.78 2.78 0 0 0 1.46 6.83C1 8.5 1 12 1 12s0 3.5.46 5.17a2.78 2.78 0 0 0 1.94 1.96c1.72.47 8.6.47 8.6.47s6.88 0 8.6-.47a2.78 2.78 0 0 0 1.94-1.96C23 15.5 23 12 23 12zM9.8 15.3V8.7l5.7 3.3-5.7 3.3z"></path></svg>
                                            @break
                                        @case('tt')
                                            <svg viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M16.6 5.82c-.9-.8-1.47-1.94-1.6-3.2h-3.14v13.3c0 1.55-1.26 2.8-2.8 2.8a2.8 2.8 0 0 1 0-5.6c.28 0 .55.04.8.12V9.98a6.06 6.06 0 0 0-.8-.05A6.06 6.06 0 1 0 15.86 16V9.34a9.14 9.14 0 0 0 5.14 1.58V7.78a5.7 5.7 0 0 1-4.4-1.96z"></path></svg>
                                    @endswitch
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

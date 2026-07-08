<div class="jtc-modal-scrim" :class="authOpen && 'is-open'" @click="authOpen = false" x-cloak>
    <div class="jtc-modal" @click.stop>
        <div class="jtc-modal__head">
            <button class="jtc-modal__close" aria-label="Close" @click="authOpen = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
            <span class="jtc-modal__logo"><img src="{{ asset('images/jtc-logo.jpeg') }}" alt=""></span>
            <h3 x-text="authTitle"></h3>
            <p x-text="authSubtitle"></p>
        </div>

        <form class="jtc-form" @submit.prevent="submitAuth($event)">
            <label x-show="authMode === 'signup'" x-cloak>Full name
                <input type="text" placeholder="Your name">
            </label>
            <label>Email or phone
                <input type="email" placeholder="you@example.com">
            </label>
            <label>Password
                <input type="password" placeholder="••••••••">
            </label>

            <div class="jtc-form__row" x-show="authMode === 'login'">
                <label class="jtc-form__check"><input type="checkbox">Remember me</label>
                <a href="#" class="jtc-form__link" @click.prevent>Forgot password?</a>
            </div>

            <button type="submit" class="jtc-btn jtc-btn--primary jtc-btn--block jtc-form__submit" x-text="authSubmitText"></button>

            <div class="jtc-form__divider"><span></span>or<span></span></div>

            <div class="jtc-form__social">
                <button type="button" class="jtc-form__oauth" @click.prevent>
                    <svg viewBox="0 0 24 24" width="17" height="17"><path fill="#4285F4" d="M23.5 12.3c0-.8-.1-1.6-.2-2.3H12v4.5h6.5c-.3 1.5-1.1 2.8-2.4 3.6v3h3.9c2.3-2.1 3.5-5.2 3.5-8.8z"/><path fill="#34A853" d="M12 24c3.2 0 6-1.1 8-2.9l-3.9-3c-1.1.7-2.5 1.2-4.1 1.2-3.1 0-5.8-2.1-6.7-5H1.3v3.1C3.3 21.3 7.3 24 12 24z"/><path fill="#FBBC05" d="M5.3 14.3c-.2-.7-.4-1.5-.4-2.3s.1-1.6.4-2.3V6.6H1.3C.5 8.2 0 10 0 12s.5 3.8 1.3 5.4l4-3.1z"/><path fill="#EA4335" d="M12 4.8c1.8 0 3.3.6 4.6 1.8l3.4-3.4C18 1.2 15.2 0 12 0 7.3 0 3.3 2.7 1.3 6.6l4 3.1c.9-2.9 3.6-4.9 6.7-4.9z"/></svg>
                    Google
                </button>
                <button type="button" class="jtc-form__oauth" @click.prevent>
                    <svg viewBox="0 0 24 24" width="17" height="17" fill="#1877F2"><path d="M24 12a12 12 0 1 0-13.9 11.9v-8.4H7.1V12h3V9.4c0-3 1.8-4.6 4.5-4.6 1.3 0 2.7.2 2.7.2v2.9h-1.5c-1.5 0-2 .9-2 1.9V12h3.3l-.5 3.5h-2.8v8.4A12 12 0 0 0 24 12z"/></svg>
                    Facebook
                </button>
            </div>

            <p class="jtc-form__switch">
                <span x-text="authSwitchPrompt"></span>
                <a href="#" @click.prevent="toggleAuthMode()" x-text="authSwitchAction"></a>
            </p>
        </form>
    </div>
</div>

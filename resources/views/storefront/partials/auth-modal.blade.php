<div class="jtc-modal-scrim" :class="authOpen && 'is-open'" @click="authOpen = false" x-cloak>
    <div class="jtc-modal" @click.stop>
        <div class="jtc-modal__head">
            <button class="jtc-modal__close" aria-label="Close" @click="authOpen = false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><line x1="6" y1="6" x2="18" y2="18"></line><line x1="18" y1="6" x2="6" y2="18"></line></svg>
            </button>
            <span class="jtc-modal__logo"><img src="{{ asset('images/jtc-logo.jpeg') }}" alt=""></span>
            <template x-if="!authSuccess">
                <div>
                    <h3 x-text="authTitle"></h3>
                    <p x-text="authSubtitle"></p>
                </div>
            </template>
            <template x-if="authSuccess">
                <div>
                    <h3 x-text="authSuccessTitle"></h3>
                    <p x-text="authSuccessMessage"></p>
                </div>
            </template>
        </div>

        <div class="jtc-form" x-show="authSuccess" x-cloak>
            <template x-if="authMode === 'login'">
                <button type="button" class="jtc-btn jtc-btn--primary jtc-btn--block jtc-form__submit"
                        @click="authOpen = false; authSuccess = false">Continue shopping</button>
            </template>
            <template x-if="authMode === 'signup'">
                <button type="button" class="jtc-btn jtc-btn--primary jtc-btn--block jtc-form__submit"
                        @click="authSuccess = false; authMode = 'login'">Login</button>
            </template>
        </div>

        <form class="jtc-form" @submit.prevent="submitAuth()" x-show="!authSuccess">
            <p class="jtc-form__error" x-show="authError" x-cloak x-text="authError"></p>

            <label x-show="authMode === 'signup'" x-cloak>Full name
                <input type="text" x-model="authForm.name">
            </label>

            <label x-show="authMode === 'signup'" x-cloak>Email
                <input type="email" placeholder="you@example.com" x-model="authForm.email">
            </label>
            <label x-show="authMode === 'signup'" x-cloak>Phone
                <input type="tel" placeholder="01XXXXXXXXX" x-model="authForm.phone">
            </label>
            <label x-show="authMode === 'login'">Email or phone
                <input type="text" placeholder="you@example.com or phone number" x-model="authForm.login">
            </label>

            <label>Password
                <input type="password" placeholder="••••••••" x-model="authForm.password">
            </label>
            <label x-show="authMode === 'signup'" x-cloak>Confirm password
                <input type="password" placeholder="••••••••" x-model="authForm.passwordConfirmation">
            </label>

            <div class="jtc-form__row" x-show="authMode === 'login'">
                <label class="jtc-form__check"><input type="checkbox" x-model="authForm.remember">Remember me</label>
                <a href="#" class="jtc-form__link" @click.prevent>Forgot password?</a>
            </div>

            <button type="submit" class="jtc-btn jtc-btn--primary jtc-btn--block jtc-form__submit"
                    :disabled="authLoading" x-text="authLoading ? 'Please wait…' : authSubmitText"></button>



            <p class="jtc-form__switch">
                <span x-text="authSwitchPrompt"></span>
                <a href="#" @click.prevent="toggleAuthMode()" x-text="authSwitchAction"></a>
            </p>
        </form>
    </div>
</div>

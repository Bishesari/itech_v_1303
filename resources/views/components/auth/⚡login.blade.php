<?php
use Livewire\Component;
new class extends Component
{
    public string $user_name = '';
    public string $password = '';
    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
        'user_name' => ['required', 'string', 'max:25'],
        'password' => ['required', 'string'],
        ]);
        
        $key = sprintf('login:%s:%s',Str::lower($this->user_name), request()->ip());

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            $this->addError(
                'user_name', "تعداد تلاش‌های ناموفق زیاد است. {$seconds} ثانیه دیگر تلاش کنید."
            );
        return;
    }

    $credentials = [
        'user_name' => $this->user_name,
        'password' => $this->password,
        ];

    if (! Auth::attempt($credentials, $this->remember)) {

        RateLimiter::hit($key, 60);

        $this->addError(
            'password',
            'نام کاربری یا رمز عبور اشتباه است.'
        );

        return;
    }

    $user = Auth::user();

    if (! $user->is_active) {

        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->addError(
            'user_name',
            'حساب کاربری شما غیرفعال شده است.'
        );

        return;
    }

    session()->regenerate();

    RateLimiter::clear($key);

    $user->update([
        'last_login_at' => now(),
    ]);

    $this->redirectRoute('select-role', navigate: true);
    }
};
?>

<div class="flex flex-col">
    <x-auth-header :title="__('ورود به حساب کاربری')"
                   :description="__('نام کاربری (کدملی) و پسورد قبلا پیامک شده است.')"/>
    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>
    <form wire:submit.prevent="login" class="space-y-4 flex flex-col mt-5" autocomplete="off">
        <x-my.flt-lbl name="user_name" label="{{__('نام کاربری:')}}" dir="ltr" maxlength="25"
                      class="tracking-wider font-semibold" autofocus required/>

        <x-my.flt-lbl name="password" type="password" label="{{__('کلمه عبور:')}}" dir="ltr" maxlength="25"
                      class="tracking-wider font-semibold" required/>

        <div class="flex justify-between">
            <!-- Remember Me -->
            <flux:field variant="inline">
                <flux:checkbox wire:model="remember" class="cursor-pointer"/>
                <flux:label class="cursor-pointer">{{__('بخاطرسپاری')}}</flux:label>
            </flux:field>
{{--            <flux:link class="text-sm" :href="route('forgotten.password')" wire:navigate tabindex="-1">--}}
{{--                {{ __('ریست کلمه عبور') }}--}}
{{--            </flux:link>--}}
        </div>

        <div class="flex items-center justify-end">
            <flux:button variant="primary" color="violet" type="submit" class="w-full cursor-pointer"
                         data-test="login-button">
                {{ __('ورود') }}
            </flux:button>
        </div>
    </form>

   
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400 mt-4">
            <span>{{ __('حساب کاربری ندارید؟') }}</span>
            <flux:modal.trigger name="register">
                <flux:button variant="ghost" icon:trailing="arrow-up-left"
                             x-on:click="$flux.modal('login').close()" size="sm"
                             class="cursor-pointer">{{ __('ثبت نام کنید.') }}</flux:button>
            </flux:modal.trigger>
        </div>
  
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400 mt-4">
            <span>{{ __('حساب کاربری ندارید؟') }}</span>
            <flux:button :href="route('register')" wire:navigate variant="ghost" icon:trailing="arrow-up-left" size="sm"
                         class="cursor-pointer">{{ __('ثبت نام کنید.') }}
            </flux:button>
        </div>
   

</div>

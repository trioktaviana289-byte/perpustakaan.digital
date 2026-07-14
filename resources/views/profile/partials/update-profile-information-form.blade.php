<section style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <header style="margin-bottom: 24px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0 0 6px 0;">
            {{ __('Profile Information') }}
        </h2>
        <p style="font-size: 13px; color: #64748b; margin: 0;">
            {{ __("Update your account's profile information, email address, and avatar.") }}
        </p>
    </header>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
        @csrf
        @method('patch')

        <div style="display: flex; align-items: center; gap: 20px; padding: 20px; background-color: #f0f9ff; border: 1px solid #e0f2fe; border-radius: 16px; box-shadow: 0 4px 12px rgba(56,189,248,0.05);">
            
            <div style="flex-shrink: 0; position: relative; width: 80px; height: 80px;">
                @if($user->avatar)
                    <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="Profile" 
                         style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); outline: 2px solid #38bdf8;">
                @else
                    <div id="avatar-placeholder" 
                         style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #38bdf8, #3b82f6); display: flex; align-items: center; justify-content: center; color: #ffffff; font-size: 24px; font-weight: 900; border: 3px solid #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); outline: 2px solid #38bdf8; text-transform: uppercase;">
                        {{ substr($user->name, 0, 2) }}
                    </div>
                    <img id="avatar-preview" alt="Profile" 
                         style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #ffffff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); outline: 2px solid #38bdf8; display: none;">
                @endif
            </div>

            <div style="flex: 1; display: flex; flex-direction: column; gap: 6px; text-align: left;">
                <div>
                    <span style="display: block; font-size: 11px; font-weight: 700; color: #0369a1; text-transform: uppercase; letter-spacing: 0.05em;">Foto Profil</span>
                    <p style="font-size: 11px; color: #94a3b8; margin: 2px 0 0 0;">Pilih foto terbaikmu untuk identitas perpustakaan</p>
                </div>

                <div style="margin-top: 4px;">
                    <input type="file" name="avatar" id="avatar-input" accept="image/*" 
                        class="block w-full text-xs text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-xl file:border-0
                        file:text-xs file:font-bold
                        file:bg-sky-100 file:text-sky-700
                        hover:file:bg-sky-200 file:cursor-pointer 
                        focus:outline-none transition-all" />
                </div>

                <p style="font-size: 11px; font-weight: 500; color: #94a3b8; margin: 2px 0 0 0; display: flex; align-items: center; gap: 4px;">
                    <span>ℹ️</span> Format: JPG, PNG atau GIF (Maks. 2MB)
                </p>
                
                @if($errors->has('avatar'))
                    <p style="font-size: 12px; color: #ef4444; font-weight: 600; margin: 4px 0 0 0;">{{ $errors->first('avatar') }}</p>
                @endif
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label for="name" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; tracking-spacing: 0.05em; padding-left: 4px;">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                style="width: 100%; padding: 12px 16px; background-color: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; color: #334155; font-weight: 500; box-sizing: border-box; transition: all 0.2s;"
                onfocus="this.style.borderColor='#38bdf8'; this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 0 0 4px rgba(56, 189, 248, 0.15)';"
                onblur="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none';" />
            @if($errors->has('name'))
                <p style="font-size: 12px; color: #ef4444; font-weight: 500; margin: 2px 0 0 4px;">{{ $errors->first('name') }}</p>
            @endif
        </div>

        <div style="display: flex; flex-direction: column; gap: 6px;">
            <label for="email" style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; tracking-spacing: 0.05em; padding-left: 4px;">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                style="width: 100%; padding: 12px 16px; background-color: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 12px; font-size: 14px; color: #334155; font-weight: 500; box-sizing: border-box; transition: all 0.2s;"
                onfocus="this.style.borderColor='#38bdf8'; this.style.backgroundColor='#ffffff'; this.style.boxShadow='0 0 0 4px rgba(56, 189, 248, 0.15)';"
                onblur="this.style.borderColor='#e2e8f0'; this.style.backgroundColor='#f8fafc'; this.style.boxShadow='none';" />
            @if($errors->has('email'))
                <p style="font-size: 12px; color: #ef4444; font-weight: 500; margin: 2px 0 0 4px;">{{ $errors->first('email') }}</p>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
            <button type="submit" style="background-color: #38bdf8; border: none; padding: 12px 28px; color: #ffffff; font-size: 13px; font-weight: 800; border-radius: 12px; letter-spacing: 0.05em; text-transform: uppercase; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(56, 189, 248, 0.2);"
                onmouseover="this.style.backgroundColor='#0284c7'; this.style.transform='translateY(-1px)';"
                onmouseout="this.style.backgroundColor='#38bdf8'; this.style.transform='translateY(0)';"
                onmousedown="this.style.transform='translateY(1px)'">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" 
                   style="font-size: 13px; font-weight: 600; color: #10b981; margin: 0;">
                    {{ __('Saved successfully.') }}
                </p>
            @endif
        </div>
    </form>
</section>

<script>
    document.getElementById('avatar-input').onchange = evt => {
        const [file] = evt.target.files
        if (file) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');
            
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
            
            if(placeholder) {
                placeholder.style.display = 'none';
            }
        }
    }
</script>
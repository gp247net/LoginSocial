# Plugin LoginSocial cho GP247

## Tổng quan

Plugin LoginSocial cho phép người dùng đăng nhập vào hệ thống GP247 bằng các tài khoản mạng xã hội như Facebook, Google, GitHub và nhiều providers khác. Plugin hỗ trợ đa guards (admin, customer, vendor, pmo...) cho phép linh hoạt trong việc quản lý xác thực người dùng.

## Tính năng

- ✅ Hỗ trợ đăng nhập qua Facebook, Google, GitHub
- ✅ Hỗ trợ nhiều guards: admin, customer, vendor, pmo
- ✅ Tự động tạo tài khoản mới khi đăng nhập lần đầu
- ✅ Liên kết tài khoản social với tài khoản hiện có
- ✅ Quản lý cấu hình OAuth trong Admin panel
- ✅ Giao diện admin thân thiện và dễ sử dụng
- ✅ Đa ngôn ngữ (Tiếng Việt, Tiếng Anh)

## Yêu cầu hệ thống

- GP247 Core >= 1.2
- Laravel 12.x
- PHP >= 8.2
- Laravel Socialite ^5.0

## Cài đặt

### Bước 1: Cài đặt package Laravel Socialite

```bash
composer require laravel/socialite
```

### Bước 2: Cài đặt plugin

1. Copy thư mục plugin vào `app/GP247/Plugins/LoginSocial`
2. Truy cập Admin Panel > Extensions > Plugins
3. Tìm "LoginSocial" và click "Install"
4. Sau khi cài đặt thành công, click "Enable" để kích hoạt plugin

### Bước 3: Cấu hình OAuth Providers

> **Lưu ý**: Plugin LoginSocial chỉ sử dụng cấu hình từ database, không sử dụng ENV variables.

#### Cấu hình Facebook Login

1. Truy cập [Facebook Developers](https://developers.facebook.com/)
2. Tạo một ứng dụng mới hoặc sử dụng ứng dụng có sẵn
3. Trong phần Settings > Basic:
   - Lấy **App ID** (Client ID)
   - Lấy **App Secret** (Client Secret)
4. Trong phần Products > Facebook Login > Settings:
   - Thêm **Redirect URL**: `https://your-domain.com/auth/social/facebook/callback`
5. **Cấu hình trong Admin Panel** (không cần ENV variables)

#### Cấu hình Google Login

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project có sẵn
3. Bật Google+ API
4. Tạo OAuth 2.0 credentials:
   - Application type: Web application
   - Authorized redirect URIs: `https://your-domain.com/auth/social/google/callback`
5. Lấy **Client ID** và **Client Secret**
6. **Cấu hình trong Admin Panel** (không cần ENV variables)

#### Cấu hình GitHub Login

1. Truy cập [GitHub Developer Settings](https://github.com/settings/developers)
2. Click "New OAuth App"
3. Điền thông tin:
   - Application name: Tên ứng dụng của bạn
   - Homepage URL: `https://your-domain.com`
   - Authorization callback URL: `https://your-domain.com/auth/social/github/callback`
4. Lấy **Client ID** và **Client Secret**
5. **Cấu hình trong Admin Panel** (không cần ENV variables)

### Bước 4: Cấu hình trong Admin Panel

1. Truy cập **Admin Panel > Plugins > LoginSocial**
2. Bật/tắt các providers mong muốn
3. Nhập Client ID, Client Secret cho mỗi provider
4. Xác nhận Redirect URL (callback URL) đã đúng
5. Click "Save Configuration"

> **Quan trọng**: Tất cả cấu hình được lưu trong database, không cần thiết lập ENV variables.

## Cấu Hình Bảo Mật

### Cấu Hình Guards

Vì lý do bảo mật, **mặc định chỉ guard `customer` được bật**. Điều này ngăn chặn việc truy cập trái phép vào các khu vực quản trị hoặc nhạy cảm của hệ thống thông qua đăng nhập social.

#### Trạng Thái Guards

Giao diện admin hiển thị trạng thái của mỗi guard:
- 🟢 **Badge màu xanh với ✓** = Guard đã được bật và có thể sử dụng đăng nhập social
- ⚫ **Badge màu xám với ✗** = Guard đã bị tắt (không cho phép đăng nhập social)

#### Bật Guards Khác

Nếu bạn cần bật đăng nhập social cho các guard khác (admin, vendor, pmo), bạn phải cấu hình thủ công trong file config:

**File**: `app/GP247/Plugins/LoginSocial/config.php`

```php
'guards' => [
    'admin' => [
        'model' => 'GP247\Core\Models\AdminUser',
        'redirect_after_login' => 'admin.home',
        'enabled' => 1, // Đổi từ 0 sang 1 để bật
        'status_default' => 0, // Trạng thái mặc định cho user mới (0=chưa kích hoạt, 1=đã kích hoạt)
    ],
    'customer' => [
        'model' => 'GP247\Shop\Models\ShopCustomer',
        'redirect_after_login' => 'front.home',
        'enabled' => 1, // Đã được bật mặc định
        'status_default' => 1, // Customer mới được kích hoạt ngay
    ],
    'vendor' => [
        'model' => 'App\GP247\Plugins\MultiVendorPro\Models\VendorUser',
        'redirect_after_login' => 'vendor_admin.home',
        'enabled' => 0, // Tắt mặc định
        'status_default' => 0, // Vendor mới cần được duyệt
    ],
    // ... các guards khác
],
```

#### Các Tham Số Cấu Hình

- **`enabled`**: `1` = Cho phép đăng nhập social cho guard này, `0` = Tắt đăng nhập social
- **`status_default`**: Trạng thái mặc định cho user mới tạo (0 = chưa kích hoạt/cần duyệt, 1 = kích hoạt ngay)
- **`model`**: Class model của user cho guard này
- **`redirect_after_login`**: Tên route để chuyển hướng sau khi đăng nhập thành công

> ⚠️ **Cảnh Báo Bảo Mật**: Việc bật đăng nhập social cho admin, vendor hoặc các guard có quyền cao khác có thể gây rủi ro bảo mật. Chỉ bật khi bạn hiểu rõ hậu quả và đã có các biện pháp bảo mật phù hợp.

### Thực Hành Tốt Nhất

1. **Giữ guard admin ở trạng thái tắt** trừ khi thực sự cần thiết
2. **Đặt `status_default` là 0** cho các guard có quyền cao (yêu cầu duyệt thủ công)
3. **Theo dõi hoạt động đăng nhập social** trong logs của bạn
4. **Triển khai xác thực bổ sung** cho các guard nhạy cảm
5. **Xem xét cấu hình guards** thường xuyên

## Sử dụng

### Thêm nút đăng nhập Social vào template

#### Đăng nhập cho Customer (mặc định)

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Đăng nhập bằng Facebook
</a>

<a href="{{ route('social.redirect', ['provider' => 'google']) }}" class="btn btn-danger">
    <i class="fab fa-google"></i> Đăng nhập bằng Google
</a>

<a href="{{ route('social.redirect', ['provider' => 'github']) }}" class="btn btn-dark">
    <i class="fab fa-github"></i> Đăng nhập bằng GitHub
</a>
```

#### Đăng nhập cho Admin

```blade
<a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'admin']) }}" class="btn btn-primary">
    <i class="fab fa-google"></i> Admin Login với Google
</a>
```

#### Đăng nhập cho Vendor

```blade
<a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'vendor']) }}" class="btn btn-primary">
    <i class="fab fa-facebook"></i> Vendor Login với Facebook
</a>
```

### Sử dụng Render Component (Khuyến nghị)

#### Tham số của Render Component

| Tham số | Kiểu | Mặc định | Mô tả |
|---------|------|----------|-------|
| `guard` | string | `'customer'` | Guard xác thực (admin, customer, vendor, pmo) |
| `providers` | array | `['facebook', 'google', 'github']` | Danh sách providers hiển thị |
| `buttonClass` | string | `'btn btn-block mb-2'` | CSS class cho các nút |
| `title` | boolean | `false` | Hiển thị tiêu đề "Đăng nhập mạng xã hội" |
| `description` | boolean | `false` | Hiển thị mô tả "Đăng nhập nhanh bằng tài khoản mạng xã hội" |
| `forceShow` | boolean | `false` | Hiển thị ngay cả khi user đã đăng nhập |

**Lưu ý**: Mặc định, nút đăng nhập mạng xã hội chỉ hiển thị khi user **chưa đăng nhập** cho guard được chỉ định. Sử dụng `forceShow=true` để luôn hiển thị.

#### Cách 1: Sử dụng @include với render.blade.php

```blade
<!-- Cách đơn giản nhất -->
@include('Plugins/LoginSocial::render')

<!-- Với tùy chỉnh guard -->
@include('Plugins/LoginSocial::render', ['guard' => 'customer'])

<!-- Với tùy chỉnh providers -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google', 'github']
])

<!-- Với tùy chỉnh button class -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'providers' => ['facebook', 'google'],
    'buttonClass' => 'btn btn-outline-primary btn-block'
])

<!-- Với title và description -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'title' => true,
    'description' => true
])

<!-- Luôn hiển thị (ngay cả khi đã đăng nhập) -->
@include('Plugins/LoginSocial::render', [
    'guard' => 'customer',
    'forceShow' => true
])
```

#### Cách 2: Sử dụng trong Login Form

```blade
<!-- Form đăng nhập thông thường -->
<form method="POST" action="{{ route('login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
</form>

<!-- Phần đăng nhập mạng xã hội -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'customer',
        'title' => true,
        'description' => true
    ])
@endif
```

#### Cách 3: Sử dụng cho Admin Login

```blade
<!-- Admin login form -->
<form method="POST" action="{{ route('admin.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Admin Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
    </div>
    <button type="submit" class="btn btn-primary btn-block">Admin Login</button>
</form>

<!-- Social login cho admin -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'admin',
        'providers' => ['google', 'github'],
        'title' => true
    ])
@endif
```

#### Cách 4: Sử dụng cho Vendor Login

```blade
<!-- Vendor login form -->
<form method="POST" action="{{ route('vendor.login') }}">
    @csrf
    <div class="form-group">
        <input type="email" name="email" class="form-control" placeholder="Vendor Email" required>
    </div>
    <div class="form-group">
        <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
    </div>
    <button type="submit" class="btn btn-success btn-block">Vendor Login</button>
</form>

<!-- Social login cho vendor -->
@if(gp247_extension_check_active('Plugins', 'LoginSocial'))
    @include('Plugins/LoginSocial::render', [
        'guard' => 'vendor',
        'providers' => ['facebook', 'google'],
        'title' => true,
        'description' => true
    ])
@endif
```

### Ví dụ hoàn chỉnh trong Login Form (Manual)

```blade
<div class="social-login-buttons">
    <h4>Hoặc đăng nhập bằng</h4>
    
    <div class="btn-group">
        @if(gp247_config('facebook_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'facebook', 'guard' => 'customer']) }}" 
           class="btn btn-facebook">
            <i class="fab fa-facebook-f"></i> Facebook
        </a>
        @endif
        
        @if(gp247_config('google_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'google', 'guard' => 'customer']) }}" 
           class="btn btn-google">
            <i class="fab fa-google"></i> Google
        </a>
        @endif
        
        @if(gp247_config('github_enabled', GP247_STORE_ID_GLOBAL, '0'))
        <a href="{{ route('social.redirect', ['provider' => 'github', 'guard' => 'customer']) }}" 
           class="btn btn-github">
            <i class="fab fa-github"></i> GitHub
        </a>
        @endif
    </div>
</div>
```

## Guards có sẵn

Plugin hỗ trợ các guards sau:

- **admin**: Đăng nhập cho quản trị viên
- **customer**: Đăng nhập cho khách hàng (mặc định)
- **vendor**: Đăng nhập cho nhà cung cấp (yêu cầu MultiVendorPro plugin)
- **pmo**: Đăng nhập cho PMO users

## Luồng xử lý

1. Người dùng click vào nút "Đăng nhập bằng Facebook/Google/GitHub"
2. Hệ thống chuyển hướng đến provider để xác thực
3. Sau khi xác thực thành công, provider chuyển hướng về callback URL
4. Plugin kiểm tra:
   - Nếu đã có social account → Đăng nhập luôn
   - Nếu email đã tồn tại → Liên kết social account với tài khoản hiện có
   - Nếu là người dùng mới → Tạo tài khoản mới và đăng nhập
5. Chuyển hướng người dùng đến trang phù hợp theo guard

## Xử lý lỗi thường gặp

### Lỗi: "This login provider is not enabled"

**Nguyên nhân**: Provider chưa được bật hoặc chưa cấu hình

**Giải pháp**:
1. Vào Admin Panel > Plugins > LoginSocial
2. Kiểm tra provider đã được enable
3. Kiểm tra Client ID và Client Secret đã được nhập đúng

### Lỗi: "Invalid authentication guard"

**Nguyên nhân**: Guard không tồn tại trong cấu hình

**Giải pháp**:
- Kiểm tra guard trong file `config.php` của plugin
- Đảm bảo guard và model tương ứng đã được cấu hình đúng

### Lỗi: "Client error: 400"

**Nguyên nhân**: Cấu hình OAuth không đúng

**Giải pháp**:
1. Kiểm tra Client ID và Client Secret
2. Kiểm tra Redirect URL khớp với cấu hình trong OAuth app
3. Đảm bảo ứng dụng OAuth đã được approved/published

## Bảo mật

- ✅ Sử dụng OAuth 2.0 protocol chuẩn
- ✅ Client Secret được lưu an toàn trong database
- ✅ Kiểm tra trạng thái tài khoản trước khi đăng nhập
- ✅ Random password cho tài khoản mới
- ✅ Session-based authentication

## Hỗ trợ

- Email: support@gp247.net
- Website: https://gp247.net

## License

MIT License

## Changelog

### Version 1.0
- Release đầu tiên
- Hỗ trợ Facebook, Google, GitHub
- Hỗ trợ nhiều guards
- Admin panel configuration
- Đa ngôn ngữ

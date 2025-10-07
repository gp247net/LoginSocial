<?php
return [
    'title' => 'Đăng nhập mạng xã hội',
    
    'admin' => [
        'title' => 'Đăng nhập mạng xã hội',
        'description' => 'Cấu hình các nhà cung cấp đăng nhập mạng xã hội cho ứng dụng. Người dùng có thể đăng nhập bằng tài khoản mạng xã hội.',
        'available_guards' => 'Guards có sẵn',
        'guards_help' => 'Các guards này có thể được sử dụng cho đăng nhập mạng xã hội. Chỉ định guard trong URL đăng nhập.',
        'enable_provider' => 'Bật đăng nhập :provider',
        'client_id' => 'Client ID / App ID',
        'client_secret' => 'Client Secret / App Secret',
        'secret_hidden' => 'Secret key đã được ẩn để bảo mật. Nhập giá trị mới để cập nhật.',
        'secret_placeholder' => '******** (Nhập giá trị mới để cập nhật)',
        'secret_new_placeholder' => 'Nhập Client Secret mới',
        'redirect_url' => 'URL chuyển hướng / Callback URL',
        'redirect_help' => 'Sao chép URL này vào cấu hình ứng dụng OAuth của bạn',
        'usage_title' => 'Ví dụ sử dụng',
        'usage_description' => 'Thêm đoạn mã này vào trang đăng nhập để bật đăng nhập mạng xã hội:',
        'save' => 'Lưu cấu hình',
        'save_success' => 'Lưu cấu hình thành công!',
        'config' => [
            'facebook_enabled' => 'Bật đăng nhập Facebook',
            'facebook_client_id' => 'Facebook App ID',
            'facebook_client_secret' => 'Facebook App Secret',
            'facebook_redirect' => 'Facebook Redirect URL',
            'google_enabled' => 'Bật đăng nhập Google',
            'google_client_id' => 'Google Client ID',
            'google_client_secret' => 'Google Client Secret',
            'google_redirect' => 'Google Redirect URL',
            'github_enabled' => 'Bật đăng nhập GitHub',
            'github_client_id' => 'GitHub Client ID',
            'github_client_secret' => 'GitHub Client Secret',
            'github_redirect' => 'GitHub Redirect URL',
        ],
    ],
    
    // Thông báo frontend
    'provider_not_enabled' => 'Nhà cung cấp đăng nhập này chưa được bật hoặc cấu hình chưa đúng.',
    'invalid_guard' => 'Guard xác thực không hợp lệ.',
    'account_inactive' => 'Tài khoản của bạn đã bị vô hiệu hóa. Vui lòng liên hệ quản trị viên.',
    'create_user_failed' => 'Không thể tạo tài khoản người dùng. Vui lòng thử lại.',
    'login_success' => 'Đăng nhập thành công!',
    'login_failed' => 'Đăng nhập thất bại. Vui lòng thử lại.',
    
    // Nút đăng nhập social
    'login_with_facebook' => 'Đăng nhập bằng Facebook',
    'login_with_google' => 'Đăng nhập bằng Google',
    'login_with_github' => 'Đăng nhập bằng GitHub',
    'login_with' => 'Đăng nhập bằng',
    'or_login_with' => 'Hoặc đăng nhập bằng',
    'continue_with' => 'Tiếp tục với',
    
    // Thông báo bổ sung
    'social_login_title' => 'Đăng nhập mạng xã hội',
    'social_login_description' => 'Đăng nhập nhanh bằng tài khoản mạng xã hội của bạn',
    'connecting_to' => 'Đang kết nối với',
    'please_wait' => 'Vui lòng chờ...',
    'redirecting' => 'Đang chuyển hướng...',
    
    // Validation messages
    'client_id_required' => 'Client ID là bắt buộc khi bật provider',
    'client_secret_required' => 'Client Secret là bắt buộc khi bật provider',
    'redirect_url_required' => 'Redirect URL là bắt buộc khi bật provider',
    'invalid_client_id' => 'Client ID không hợp lệ',
    'invalid_client_secret' => 'Client Secret không hợp lệ',
    'invalid_redirect_url' => 'Redirect URL không hợp lệ',
    
    // Success messages
    'account_linked' => 'Tài khoản đã được liên kết thành công',
    'account_created' => 'Tài khoản mới đã được tạo thành công',
    'welcome_message' => 'Chào mừng bạn đến với hệ thống!',
];

# 用户名生成头像工具

```
composer require ella123/hyperf-generate-avatar
```

```php
use Ella123\HyperfGenerateAvatar\AvatarUtils;
// 生成四个字的头像
AvatarUtils::generateAvatar(username: '织梦行云')
// 生成字母头像
AvatarUtils::generateAvatar(username: 'ELLA123')
```
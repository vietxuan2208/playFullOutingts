Place a TrueType font file to be used by the captcha generator here.

Recommended steps:
1. Download a free TTF (e.g., DejaVuSans-Bold.ttf or OpenSans-Regular.ttf).
   - DejaVu: https://www.fontsquirrel.com/fonts/dejavu-sans
   - Open Sans: https://fonts.google.com/specimen/Open+Sans
2. Rename the downloaded file to `captcha.ttf` and put it in `public/fonts/`.

The captcha generator looks for `public/fonts/captcha.ttf`. If not found or if PHP GD/TTF is not available, it will fall back to a simpler built-in font renderer.

Requirements:
- PHP GD extension with FreeType support (imagettftext). On Windows, enable `php_gd2` and ensure FreeType is available.
- File path: `public/fonts/captcha.ttf` (relative to Laravel project root).
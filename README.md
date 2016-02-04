**Text2Image** — component for convert text to image using ttf fonts. Might be helpful for security purpose against spam-bots personal data scanners (e.g. email and phone numbers).
Also you can make image placeholder, like placehold.it service.

##Examples

Call snippet:
```
[[text2image?text=`Hello world!`]]
```
By default generates a simple PNG image with transparent background in base64 encoding.

![text2image Hello world](https://file.modx.pro/files/0/5/d/05dcbf23b7b635485cc035883c9c2d5c.png)

Generates a placeholder image 100x100 pixels:

```
[[text2image?&w=`100`&h=`100`]]
```
![text2image Hello world](https://file.modx.pro/files/9/3/1/9310fc072b7af00b019452d8a8ad3128.png)

You can specify the text color, font, size, angle, space, background color and image size in snippet parameters.
Snippet call with different parameters:
```
[[text2image?
    &text=`Text2Image`
    &color=`#FFD700`
    &bg=`#000`
    &angle=`-45`
    &format=`jpeg`
    &fontFile=`[[+assetsPath]]fonts/myFavouriteFont.ttf`
]]
```

![text2image different parameters](https://file.modx.pro/files/b/e/e/beedc32578b5e64b1e1582283a348a07.png)

You can upload your own fonts, for this purpose you need to specify the path to the font, using `[[++assets_path]]` placeholder . Or put your font to the component fonts directory: /assets/components/text2image/fonts, then the path should be, as in the example above using the placeholder `[[+assetsPath]]`.

##Parameters list

***w** — Ширина изображения.
***h** — Высота изображения.
***fontSize** — Размер шрифта в пунктах (pt).
***fontFile** — Файл со шрифтом в формате TrueType (.ttf).
***angle** — Угол наклона текста на изображение.
***padding** — Отступ вокруг текста на изображение.
***bg** — Цвет фона в режиме HEX (#fff).
***color** — Цвета текста в режиме HEX (#000).
***trp** — Прозрачность фона.
***format** — Формат изображения (png,gif,jpeg).
***tpl** — Чанк оформления каждого изображения.
***toPlaceholder** — Если указан этот параметр, то результат будет сохранен в плейсхолдер, вместо прямого вывода на странице.
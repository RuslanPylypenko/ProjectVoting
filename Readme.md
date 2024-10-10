## Технічне завдання: Система голосування для проектів

### 1. **Огляд проекту**

Система дозволяє користувачам створювати сесії для подання проектів, які проходять етапи модерації та голосування. Користувачі можуть подавати свої проекти, редагувати їх після модерації, а також голосувати за проекти, що пройшли модерацію. Після завершення голосування визначаються переможці на основі кількості отриманих голосів.

### 2. **Функціональні вимоги**

2.1. **Користувачі**

- Реєстрація та авторизація в системі.
- Перегляд списку проектів на етапі голосування.

2.2. **Сесії**

- Адміністратори можуть створювати сесії для голосування.
- Параметри сесії:
    - Дати початку та закінчення подання проектів.
    - Дати початку та закінчення голосування.
    - Кількість голосів, необхідна для перемоги.
    - Налаштування вікових категорій учасників.

2.3. **Проекти**

- Користувачі подають проекти, заповнюючи форму з полями:
    - Назва проекту.
    - Опис проекту.
    - Категорія проекту.
    - Дата подання.
- Етап модерації проектів:
    - Адміністратори переглядають подані проекти.
    - Затвердження або відхилення проектів.
    - Можливість редагування проектів після модерації.

2.4. **Голосування**

- Проекти, що пройшли модерацію, отримують статус «голосування».
- Користувачі можуть голосувати за кілька проектів.
- Кількість голосів обмежена налаштуваннями сесії.

2.5. **Визначення переможців**

- Автоматичне визначення переможців після завершення голосування.
- Можливість для адміністратора оголошувати результати.

2.6. **Статистика**

- Система надає огляд статистики голосування:
    - Кількість голосів за кожен проект.
    - Розподіл за статтю (чоловіки, жінки).
    - Розподіл за віковими категоріями (18–24, 25–34, 35–44, 45+).
    - Розподіл за категоріями проектів.
    - Візуалізація даних у вигляді діаграм і графіків.
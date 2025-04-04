# CRM API & Frontend

Bu loyiha mijozlar va kompaniyalar bilan ishlashni osonlashtiruvchi CRM (Customer Relationship Management) tizimi uchun backend va frontend qismlarini o'z ichiga oladi. Loyiha **Symfony** va **Vue.js** texnologiyalaridan foydalanadi.

## Backend (CRM API)

Backend qismi **Symfony** asosida qurilgan bo'lib, u API Platform yordamida RESTful API yaratish uchun mo'ljallangan. Bu qism quyidagi imkoniyatlarni taqdim etadi:

- Foydalanuvchilarni yaratish, o'zgartirish va o'chirish
- Kompaniyalar va mijozlar ma'lumotlarini boshqarish
- JWT (JSON Web Token) yordamida autentifikatsiya
- Rasm va fayllarni yuklash va saqlash

## Frontend

Frontend qismi **Vue.js** yordamida ishlab chiqilgan bo'lib, foydalanuvchilar uchun qulay va intuitiv interfeys yaratadi. Bu qism quyidagi imkoniyatlarni taqdim etadi:

- Barcha CRM tizimi uchun foydalanuvchi interfeysi
- Rasm va fayllar bilan ishlash
- Foydalanuvchi ro'yxati va kompaniyalar bilan ishlash
- Foydalanuvchi uchun izohlar, bildirishnomalar va boshqa interaktiv elementlar

## Loyiha Tuzilishi

Loyiha ikkita asosiy qismdan iborat:

1. **Backend (CRM API)**: 
   - Symfony 7.1, API Platform 3.1, PHP 8.2 texnologiyalaridan foydalanadi.
   - `crm-api` papkasida joylashgan.

2. **Frontend (CRM Interface)**:
   - Vue.js va Tailwind CSS yordamida ishlab chiqilgan.
   - `my-crm` papkasida joylashgan.

## Talablar

### Backend

- PHP 8.2+
- Composer
- Symfony 7.1
- API Platform 3.1
- Doctrine ORM

### Frontend

- Node.js (>= 16.0)
- npm yoki yarn
- Vue.js
- Tailwind CSS
- Vite

## O'rnatish

### Backend

1. **Composer orqali kerakli paketlarni o'rnating**:

   ```bash
   composer install
2. .env faylini sozlang:

   ```bash
   cp .env.example .env

3. Migratsiyalarni bajarish:

   ```bash
   bin/console doctrine:migrations:migrate

4. Serverni ishga tushiring:

   ```bash
   symfony server:start

### Frontend

1. Node paketlarini o'rnating:

   ```bash
   npm install

2. Vite serverini ishga tushiring:

   ```bash
   npm run dev

##Xususiyatlar

Foydalanuvchi boshqaruvi: Yangi foydalanuvchilarni ro'yxatdan o'tkazish, ma'lumotlarini yangilash, va foydalanuvchi rollarini boshqarish imkoniyatlari.

Kompyuterlashgan tizim: Kompaniyalar va mijozlar bilan bog'liq barcha ma'lumotlarni boshqarish.

JWT autentifikatsiya: Foydalanuvchilarga xavfsiz tizimga kirish imkoniyatini beradi.

Interaktiv interfeys: Foydalanuvchi uchun qulay va intutitiv interfeys taqdim etiladi.

## Hamma uchun
Bu CRM tizimi mijozlar va kompaniyalar bilan ishlashni osonlashtiradi, foydalanuvchi ma'lumotlarini boshqarishni soddalashtiradi va tizimning samaradorligini oshiradi.

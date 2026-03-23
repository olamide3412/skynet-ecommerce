# Skynet Digital AI Agent Instructions

These instructions outline the core technologies, coding style, and architectural patterns used in this project. Whenever modifying or creating new code, strictly adhere to these guidelines.

## Tech Stack Overview
- **Backend framework:** Laravel 11.x
- **Frontend framework:** Vue 3 (Composition API)
- **Routing & Data Bridge:** Inertia.js (with Ziggy for client-side routing)
- **Styling:** Tailwind CSS (built with Vite)
- **State/Notifications:** Pinia, `vue-toastification`

---

## Frontend (Vue 3 + Inertia) Guidelines

1. **Composition API Only:** Always use the `<script setup>` syntax for writing Vue components. Avoid the Options API.
2. **Inertia Navigation:** Do not use standard `<a>` tags for internal links. Replace them with Inertia's `<Link>` component to ensure SPA-like soft transitions without full page reloads.
3. **Forms & Submissions:** Use Inertia's `useForm()` helper for reactive form state, validation error handling, and AJAX submissions (e.g., `form.post(route('...'))`).
4. **Client-side Routing:** Utilize the `route('route.name')` helper provided by Ziggy within Vue templates instead of hardcoding URLs.
5. **Dark Mode Integration:** We support a fully realized dark mode. Always implement Tailwind's `dark:` variant classes alongside standard classes (e.g., `bg-white dark:bg-gray-900 text-gray-900 dark:text-white`).
6. **Toast Notifications:** Use `vue-toastification` for displaying inline feedback messages (e.g., `toast.success('Saved!')` or `toast.error('Failed!')`) after user interactions like form submissions.
7. **Component Architecture:** Maintain a strict separation between page-level views (stored in `resources/js/Pages/`) and reusable elements (stored in `resources/js/Components/`).

---

## Backend (Laravel) Guidelines

1. **Controllers & Routing:** Keep controllers skinny. They should primarily validate input, interact with models/services, and return an Inertia response (`return Inertia::render('Path/To/View', [...])`) or redirect safely (`return back()->with(...)`).
2. **Validation:** Validate all incoming HTTP request data using either Laravel Form Requests or direct array validation (`$request->validate([...])`) before processing anything.
3. **Security Standards:** Where dealing with public-facing submissions (Contact form, Login, Registration), ensure Cloudflare Turnstile CAPTCHA validation is required on the backend.
4. **Configuration Mapping:** Do not use `env('VAR_NAME')` outside of `config/` files. Always use `config('file.var')` within controllers or middleware to access environment variables.
5. **Database & Models:** Use Eloquent ORM. Keep business logic out of the views and firmly within Controllers, Models, or dedicated Service classes.

---

## Styling & Design System

1. **Tailwind First:** Rely exclusively on Tailwind CSS utility classes. Avoid writing custom CSS rules in `<style scoped>` blocks unless necessary for highly complex or specific animations.
2. **Brand Colors:** Use the pre-configured semantic classes (e.g., `text-primary`, `bg-primary`, `bg-dark`, `text-secondary`, etc.) for consistency.
3. **Responsive Design:** Ensure all components are mobile-first and fully responsive using Tailwind breakpoint prefixes (`sm:`, `md:`, `lg:`, `xl:`).

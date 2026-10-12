# Laravel + Inertia Architecture & Testing Guidelines

## Core Philosophy

This project prioritizes **SOLID**, **KISS**, and **YAGNI**. We avoid the bloat, boilerplate, and framework-fighting in favor of a pragmatic, battle-tested approach tailored for Laravel.

Clean code principles are CRITICAL and MANDATORY:

1. Single Responsibility — Every module, class, and function does one thing. If you can't describe it without "and," split it.
2. Explicit over implicit — No magic values, no hidden state, no surprise side effects. If it matters, it's named and declared.
3. Fail loud, fail early — Validate inputs at boundaries, raise meaningful errors immediately, never silently swallow exceptions or return ambiguous nulls.
4. Names are documentation — Functions and variables should read like intent, not implementation. A good name makes the comment redundant.
5. Leave the codebase cleaner than you found it — Every PR removes at least as much complexity as it adds. Refactor opportunistically; don't defer indefinitely.

## Architecture: Pragmatic Actions

We use the **Action pattern** to isolate business logic, keeping controllers thin and applications highly testable.

### 1. The HTTP Layer (Thin Controllers)
Controllers are responsible exclusively for the HTTP context.
- **Validation:** Always use Laravel `FormRequests` to validate incoming data.
- **Execution:** Inject the required Action(s) into the controller (or method) and pass the validated data.
- **Response:** Return an Inertia response (or JSON for APIs).
- **Rule:** Never put business logic, complex database queries, or 3rd-party API calls directly in a controller.

### 2. The Business Layer (Actions)
Actions contain the core business logic of the application.
- **Location:** `app/Actions`. As the app grows, group them by domain (e.g., `app/Actions/Billing`, `app/Actions/Onboarding`).
- **Naming:** Name them based on what they do, with the `Action` suffix (e.g., `CreateUserAction`, `ProcessPaymentAction`).
- **Structure:** Create dedicated classes with a single `handle()` method.
- **Dependencies:** Inject dependencies via the constructor using private readonly properties.
- **Integrity:** Wrap complex operations involving multiple models in `DB::transaction()` within the `handle()` method.
- **Reusability:** Actions can be called from Controllers, Console Commands, Jobs, or even other Actions.

### 3. The Data Layer (Active Record)
We embrace Laravel's Active Record implementation (Eloquent) rather than fighting it.
- **No Repositories:** Do not use the Repository pattern. Let Eloquent be Eloquent.
- **Scopes:** Keep complex or repetitive query constraints inside Model scopes (e.g., `User::active()->unpaid()`).
- **Direct Access:** Actions are allowed to query and manipulate Models directly.

---

## Testing Strategy

**Philosophy:** Test what *we* wrote that *matters*, at the cheapest level that gives confidence. Don't test framework behavior, non-critical flows, or implementation details.

### 1. Unit Tests (`tests/Unit/`) — Pest
- **What to test:** Pure logic. Custom validation rules, complex calculation classes, specialized data transformers, and Actions that *do not* touch the database.
- **What NOT to test:** Actions that heavily rely on the database.
- **Rule:** Do not waste time mocking Eloquent models. If a class requires the database to function realistically, it belongs in Feature tests.

### 2. Feature / Integration Tests (`tests/Feature/`) — Pest
*This will be the bulk of your test suite. Laravel excels at fast DB testing.*
- **What to test (Actions):** Instantiate the Action, pass it real data, let it hit the SQLite test database, and assert the database state changed correctly or the right Events/Jobs were dispatched.
- **What to test (HTTP Boundaries):** Test Controller endpoints (`$this->post(...)`). Assert validation fails when expected, and assert the correct Action is triggered and returns a 200/302.
- **Mocking rules:** Only mock 3rd-party APIs (Stripe, Postmark, LLMs) using Laravel's built-in `Http::fake()` or interface binding. Do *not* mock the database.

### 3. E2E Tests — Pest + Playwright
- **What to test:** 5-10 critical user journeys (e.g., login, complete onboarding, submit primary form) and 2-3 key error states.
- **What NOT to test:** Every single page, every edge case, or visual regressions.
- **Rule:** Keep these sparse. If you have more than ~15 E2E tests for an MVP, you are over-investing. These are smoke tests to ensure Inertia, the React/React frontend, and the backend communicate correctly.

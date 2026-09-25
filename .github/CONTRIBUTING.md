# Contribution Guide

Thank you for considering a contribution to Cippus. Cippus aims to give AI-assisted Laravel development a precise, editable baseline; improvements should stay focused, observable, and useful to the applications that install it.

For significant changes, please open a feature request or discussion first so we can agree on the problem and approach before you invest time in a pull request.

## Process

1. Fork the project and create a focused branch from `main`.
2. Make the smallest change that solves the agreed problem.
3. Update tests and documentation when public package behavior changes.
4. Run the relevant validation, then open a pull request using the provided template.

## Guidelines

- Ensure the coding style passes by running `composer lint`.
- Run `composer test` before requesting review when practical.
- Keep commits coherent and the pull request limited to one concern.
- Contributions assisted by AI are welcome, but you must understand, review, and test every submitted change.
- You may need to [rebase](https://git-scm.com/book/en/v2/Git-Branching-Rebasing) to resolve merge conflicts.
- Please remember that we follow [SemVer](http://semver.org/).

## What makes a helpful issue

- Use the bug form for reproducible package defects.
- Use the feature-request form for a problem, its intended outcome, and realistic alternatives.
- Use GitHub Discussions for questions, adoption help, and early ideas.
- Do not report security vulnerabilities in public issues; follow the [security policy](SECURITY.md) instead.

## Setup

Clone your fork, then install the dev dependencies:

```bash
composer install
```

## Lint

Lint your code:

```bash
composer lint
```

## Tests

Run all tests:

```bash
composer test
```

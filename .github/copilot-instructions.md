# Lara-Dual-Meld — Copilot Instructions

## Project Context

- A real-time multiplayer card game built with Laravel and the TALL stack.
- A game supports exactly 4 players.
- Each player is dealt 8 cards.
- The objective is to form exactly two valid sets of 4 cards.
- A valid set can be:
    - A sequence of 4 cards in the same suit.
    - Four cards of the same rank (four-of-a-kind).
- `2` cards are wildcards and may substitute for other cards when forming a valid set.
- The game will eventually support both human players and bots.

## Tech Stack

- Framework: Laravel (Latest)
- Frontend: Livewire + Alpine.js + Tailwind CSS (Latest)
- Database: MariaDB (Latest) with migrations
- OS Environment: Ubuntu Linux

### AI Development Guidelines

- Development is intentionally incremental. Do not design or implement the entire game architecture or database upfront.
- Prefer solving the current requirement with the smallest appropriate change, while avoiding decisions that would make future game functionality unnecessarily difficult.
- When a requirement is not yet defined, do not invent permanent architecture for it. Flag the uncertainty and suggest a minimal approach.

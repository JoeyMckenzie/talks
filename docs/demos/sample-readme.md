# acme-api

A REST API for the Acme Corp widget management platform.

## Getting Started

```bash
git clone https://github.com/acme/acme-api.git
cd acme-api
cp .env.example .env
docker compose up -d
cargo run
```

## Architecture

```
src/
├── handlers/     # HTTP request handlers
├── models/       # Domain models and DB schemas
├── services/     # Business logic layer
├── middleware/    # Auth, logging, rate limiting
└── main.rs       # Entry point and router setup
```

## API Endpoints

| Method | Path | Description |
|--------|------|-------------|
| GET | `/widgets` | List all widgets |
| POST | `/widgets` | Create a widget |
| GET | `/widgets/:id` | Get widget by ID |
| PUT | `/widgets/:id` | Update a widget |
| DELETE | `/widgets/:id` | Delete a widget |
| GET | `/health` | Health check |

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `DATABASE_URL` | PostgreSQL connection string | required |
| `REDIS_URL` | Redis connection for caching | `redis://localhost:6379` |
| `PORT` | Server port | `8080` |
| `LOG_LEVEL` | Logging verbosity | `info` |
| `JWT_SECRET` | Token signing key | required |

## Running Tests

```bash
cargo test
cargo test --test integration -- --test-threads=1
```

## Contributing

1. Fork the repo
2. Create a feature branch (`git checkout -b feat/amazing-widget`)
3. Commit your changes
4. Open a PR against `main`

## License

MIT

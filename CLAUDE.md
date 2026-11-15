# Daily GPU price update (instructions for scheduled runs)

When asked to "update GPU prices" (or any daily price run), do exactly this. Work efficiently and do not write extra documents. Do not use browser tools or log into WordPress: the website (tensorcore.si/gpu-cloud-pricing/) pulls the files in `out/` from the `main` branch of this repo by itself every hour.

## 1. Setup
- `git checkout main && git pull origin main`
- `git config user.name "scienceinquiry12-max"` and `git config user.email "scienceinquiry12-max@users.noreply.github.com"`

## 2. Read yesterday's data
`data/prices.json` = `{updated, D, OTHER}`. `D` has keys `h100`, `h200`, `b200`, `a100`; each is `{n, full, rows:[[provider, pricePerGpuHour, configuration, type]]}` where type `N` = GPU cloud, `H` = hyperscaler, `M` = serverless/marketplace. `OTHER` is `[[gpuName, typicalPriceString, lowestPriceString], ...]`.

## 3. Collect today's prices (WebFetch; on-demand USD per GPU per hour; divide multi-GPU instance prices by GPU count)
Fetch, asking for the per-provider on-demand per-GPU-hour table with exact numbers:
- https://getdeploying.com/gpus/nvidia-h100
- https://getdeploying.com/gpus/nvidia-h200
- https://getdeploying.com/gpus/nvidia-b200
- https://getdeploying.com/gpus/nvidia-a100
- https://getdeploying.com/guides/cheapest-gpu-cloud
- https://gpuperhour.com/gpu-price-index
- Extra H200/H100 source: https://www.thundercompute.com/blog/nvidia-h200-pricing

## 4. Update the data
- For every existing row, set today's price for the same provider and comparable configuration. If a provider is not in today's sources, keep yesterday's price.
- Remove a row only if a source clearly shows that provider no longer offers on-demand pricing for that GPU. You may add a well-known provider a source lists (keep 12 to 25 rows per GPU, never fewer than 8).
- Sanity check: if a price moved more than 40% from yesterday, keep it only if a second source agrees; otherwise keep yesterday's value.
- Update `OTHER` (B300, GB200, RTX PRO 6000, L40S, RTX 5090, RTX 4090, MI300X, MI325X) from the cheapest-gpu-cloud guide and the price index, formatted like "$1.23" ("n/a" if unknown).
- Keep the JSON structure exactly the same. Set `updated` to today's date (YYYY-MM-DD).
- If every source failed to load, change nothing, do not commit, and report the failure.

## 5. Build and publish
- Run `node build.js`. It validates the data, appends today's medians to `data/history.json` and regenerates `out/`. If it throws, fix the data and rerun. Never hand-edit files in `out/`.
- Commit on `main` with message `Prices YYYY-MM-DD` and push directly to `main` (NOT a `claude/` branch): `git push origin main`. The owner wants this job to push to main because the website only reads `main`.
- Confirm with `git ls-remote origin main` that the remote main points to your commit.

## 6. Report
2 to 4 lines: today's medians for H100, H200, B200 and A100 (printed by build.js), the lowest price for each, and notable changes. If any step failed, give the exact error and the step.

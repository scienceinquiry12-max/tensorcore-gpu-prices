# tensorcore-gpu-prices

Daily cloud GPU price data for the [TensorCore.si GPU Cloud Price Tracker](https://tensorcore.si/gpu-cloud-pricing/).

- `data/prices.json`: on-demand USD per GPU-hour by provider for H100, H200, B200 and A100, plus a snapshot of other GPUs.
- `data/history.json`: daily median price per GPU.
- `build.js`: run `node build.js` to append today's medians and generate the files in `out/`.
- `out/`: generated page content, homepage section, category banner text and SEO metadata. The website pulls these files automatically.

Prices are compiled daily from public GPU price trackers and provider pages. Always confirm the final price with the provider.

# Soundphere — PHP + MySQL version

Full PHP/MySQL rebuild of the Soundphere headphone store: real database-backed
login, cart, and orders (Cash on Delivery only). Same design as before, now
with a proper backend instead of localStorage.

## Setup in XAMPP (step by step)

1. **Copy the folder**
   Copy the whole `soundphere-php` folder into `C:\xampp\htdocs\`
   (so `index.php` sits directly at `C:\xampp\htdocs\soundphere-php\index.php`).

2. **Start Apache and MySQL**
   Open the XAMPP Control Panel → click **Start** next to both *Apache* and *MySQL*.

3. **Create the database**
   Open `http://localhost/phpmyadmin` in your browser.
   Click **Import** → **Choose file** → select `database.sql` from this folder → click **Go**.
   This creates the `soundphere` database with 4 tables (`users`, `products`, `cart`, `orders`)
   and inserts the one product, Aura One, with its price and real product photos.

4. **Check the DB connection settings**
   Open `includes/config.php`. Default XAMPP settings are already filled in:
   ```php
   $DB_HOST = "localhost";
   $DB_USER = "root";
   $DB_PASS = "";
   $DB_NAME = "soundphere";
   ```
   If your MySQL has a different username/password, edit this file.

5. **Open the site**
   Go to `http://localhost/soundphere-php/index.php`

That's it — no `npm install`, no build step. Just PHP files XAMPP already knows how to run.

## What's different from the old version

| | Old version | This version |
|---|---|---|
| Pages | `.html` | `.php` |
| Login / accounts | Browser localStorage | MySQL `users` table (passwords hashed with `password_hash()`) |
| Cart | Browser localStorage | MySQL `cart` table, tied to your logged-in user |
| Orders | Not saved anywhere real | MySQL `orders` table — every COD order is a real database row |
| Product images | Hand-drawn SVG illustrations | Real headphone photos (free-license stock images) |
| Product info | Hardcoded in JavaScript | MySQL `products` table |

## Database tables (matches the Data Dictionary from the DFD)

- **users** — id, name, email, phone, password (hashed)
- **products** — id, name, price, old_price, description, image1, image2, image3
- **cart** — id, user_id, product_id, color, qty
- **orders** — id, order_code, user_id, product_id, qty, color, customer_name,
  phone, address, city, pincode, state, payment_mode ("COD"), total_amount, created_at

## Pages

- `index.php` — home
- `product.php` — product page, 3-image swipeable gallery, add to cart / buy now (login required)
- `login.php` / `register.php` / `logout.php` — real MySQL authentication
- `cart.php` — view / update qty / remove items (per logged-in user)
- `checkout.php` — delivery form, Cash on Delivery only, writes to the `orders` table
- `about.php`, `contact.php` — static info pages

## Notes

- Product images are hotlinked from free-license stock photo URLs (stored in the
  `products` table) — your computer needs normal internet access to display them,
  same as any website using online images.
- Passwords are stored hashed (`password_hash` / `password_verify`), never in plain text.
- This is a simple procedural-PHP build on purpose (no framework, no OOP) —
  easy to read and explain for a project submission.

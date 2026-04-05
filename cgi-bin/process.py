#!/usr/bin/env python3
# ─────────────────────────────────────────────────────────────
#  process.py — Script CGI pentru VESTES
#  Lab 4, Sarcina 2: Prelucrarea informatiei introduse de utilizator
#  Lab 4, Sarcina 3: Memorarea datelor intr-un fisier (orders.txt)
# ─────────────────────────────────────────────────────────────

import cgi
import cgitb
import os
import html
from datetime import datetime

# Afiseaza erorile Python in browser (util la depanare)
cgitb.enable()

# ── Citirea datelor trimise prin POST ──
form = cgi.FieldStorage()

firstName = html.escape(form.getvalue('firstName', '').strip())
lastName  = html.escape(form.getvalue('lastName',  '').strip())
email     = html.escape(form.getvalue('email',     '').strip())
phone     = html.escape(form.getvalue('phone',     '').strip())
category  = html.escape(form.getvalue('category',  '').strip())
size      = html.escape(form.getvalue('size',      '').strip())
message   = html.escape(form.getvalue('message',   '').strip())
timestamp = datetime.now().strftime('%Y-%m-%d %H:%M:%S')

# ── Validare server-side (Sarcina 2) ──
errors = []
if len(firstName) < 2:
    errors.append('Invalid first name.')
if len(lastName) < 2:
    errors.append('Invalid last name.')
if '@' not in email or '.' not in email.split('@')[-1]:
    errors.append('Invalid email address.')
if not any(c.isdigit() for c in phone) or len(phone) < 7:
    errors.append('Invalid phone number.')
if category not in ('clothing', 'shoes', 'accessories'):
    errors.append('Invalid category.')
if size not in ('XS', 'S', 'M', 'L', 'XL'):
    errors.append('Invalid size.')

# ── Sarcina 3: Salvarea datelor in fisier ──
if not errors:
    orders_file = os.path.join(os.path.dirname(__file__), '..', 'data', 'orders.txt')
    os.makedirs(os.path.dirname(orders_file), exist_ok=True)

    with open(orders_file, 'a', encoding='utf-8') as f:
        f.write('─' * 50 + '\n')
        f.write(f'Date:      {timestamp}\n')
        f.write(f'Name:      {firstName} {lastName}\n')
        f.write(f'Email:     {email}\n')
        f.write(f'Phone:     {phone}\n')
        f.write(f'Category:  {category}\n')
        f.write(f'Size:      {size}\n')
        if message:
            f.write(f'Notes:     {message}\n')
        f.write('\n')

# ── Raspunsul HTML trimis catre browser ──
print('Content-Type: text/html; charset=utf-8')
print()

if errors:
    # ── Pagina de eroare ──
    error_list = ''.join(f'<li>{e}</li>' for e in errors)
    print(f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Error – Vestes</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .result-section {{
            min-height: 80vh; display: flex;
            align-items: center; justify-content: center;
            padding: 80px 64px;
        }}
        .result-box {{ max-width: 480px; width: 100%; text-align: center; }}
        .result-box h2 {{
            font-size: 13px; letter-spacing: 4px;
            font-weight: 300; margin-bottom: 24px; color: #cc0000;
        }}
        .result-box ul {{
            list-style: none; font-size: 12px;
            letter-spacing: 1px; color: #555; margin-bottom: 32px;
        }}
        .result-box ul li {{ margin-bottom: 8px; }}
        .btn-back {{
            display: inline-block; padding: 14px 40px;
            border: 1px solid #000; font-size: 10px;
            letter-spacing: 3px; text-decoration: none; color: #000;
            transition: background 0.3s, color 0.3s;
        }}
        .btn-back:hover {{ background: #000; color: #fff; }}
    </style>
</head>
<body>
<header>
    <div class="logo">VESTES</div>
    <nav><ul class="menu">
        <li><a href="../index.html">HOME</a></li>
        <li><a href="clothing.html">CLOTHING</a></li>
        <li><a href="shoes.html">SHOES</a></li>
        <li><a href="accessories.html">ACCESSORIES</a></li>
        <li><a href="form.html">CONTACT</a></li>
    </ul></nav>
</header>
<section class="result-section">
    <div class="result-box">
        <h2>VALIDATION ERROR</h2>
        <ul>{error_list}</ul>
        <a href="javascript:history.back()" class="btn-back">GO BACK</a>
    </div>
</section>
<footer>© 2026 VESTES<div class="footer-contact">contact@vestes.com · Instagram @vestes</div></footer>
</body>
</html>''')

else:
    # ── Pagina de succes ──
    print(f'''<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed – Vestes</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <style>
        .result-section {{
            min-height: 80vh; display: flex;
            align-items: center; justify-content: center;
            padding: 80px 64px;
        }}
        .result-box {{ max-width: 520px; width: 100%; }}
        .result-icon {{ font-size: 28px; text-align: center; margin-bottom: 24px; }}
        .result-box h2 {{
            font-size: 13px; letter-spacing: 4px;
            font-weight: 300; margin-bottom: 8px; text-align: center;
        }}
        .result-box .sub {{
            font-size: 11px; color: #888; letter-spacing: 2px;
            text-align: center; margin-bottom: 40px;
        }}
        .summary {{
            background: #f8f8f8;
            padding: 32px;
            border-left: 2px solid #000;
            margin-bottom: 36px;
        }}
        .summary h3 {{
            font-size: 10px; letter-spacing: 4px; margin-bottom: 20px;
        }}
        .summary-row {{
            display: flex; justify-content: space-between;
            font-size: 12px; letter-spacing: 1px;
            margin-bottom: 10px; color: #555;
        }}
        .summary-row span:last-child {{ color: #000; font-weight: 500; }}
        .btn-home {{
            display: block; text-align: center;
            padding: 14px; border: 1px solid #000;
            font-size: 10px; letter-spacing: 3px;
            text-decoration: none; color: #000;
            transition: background 0.3s, color 0.3s;
        }}
        .btn-home:hover {{ background: #000; color: #fff; }}
    </style>
</head>
<body>
<header>
    <div class="logo">VESTES</div>
    <nav><ul class="menu">
        <li><a href="../index.html">HOME</a></li>
        <li><a href="../HTML/clothing.html">CLOTHING</a></li>
        <li><a href="../HTML/shoes.html">SHOES</a></li>
        <li><a href="../HTML/accessories.html">ACCESSORIES</a></li>
        <li><a href="../HTML/form.html">CONTACT</a></li>
    </ul></nav>
</header>
<section class="result-section">
    <div class="result-box">
        <div class="result-icon">✦</div>
        <h2>ORDER RECEIVED</h2>
        <p class="sub">Thank you, {firstName}. We will contact you at {email}.</p>

        <div class="summary">
            <h3>ORDER SUMMARY</h3>
            <div class="summary-row"><span>NAME</span><span>{firstName} {lastName}</span></div>
            <div class="summary-row"><span>EMAIL</span><span>{email}</span></div>
            <div class="summary-row"><span>PHONE</span><span>{phone}</span></div>
            <div class="summary-row"><span>CATEGORY</span><span>{category.upper()}</span></div>
            <div class="summary-row"><span>SIZE</span><span>{size}</span></div>
            {'<div class="summary-row"><span>NOTES</span><span>' + message + '</span></div>' if message else ''}
        </div>

        <a href="../index.html" class="btn-home">BACK TO HOME</a>
    </div>
</section>
<footer>© 2026 VESTES<div class="footer-contact">contact@vestes.com · Instagram @vestes</div></footer>
</body>
</html>''')

# Put the site online (free) with InfinityFree

InfinityFree gives free PHP + MySQL hosting with a free sub-domain like `scrapms.infinityfreeapp.com`.
It takes about 20 minutes.

## 1. Create the hosting account
1. Go to https://www.infinityfree.com and sign up (free).
2. Click **Create Account** (a hosting account), choose a free sub-domain, e.g. `scrapms.infinityfreeapp.com`.
3. Wait until the account shows **Active**, then open its **Control Panel** (vPanel).

## 2. Create the database
1. Control Panel > **MySQL Databases** > create a database named `nsp_scrap`.
2. Note the four values shown there: **MySQL Hostname**, **MySQL Username**, **MySQL Password**
   (same as the account / vPanel password, see *Account details*), and the full **Database Name**
   (e.g. `if0_12345678_nsp_scrap`).
3. Click **Admin** (phpMyAdmin) next to the database > **Import** > choose
   `database/nsp_scrap_demo.sql` from this project > **Go**.

## 3. Add the database login
1. In this project, copy `admin/config.example.php` to `admin/config.php`.
2. Put the four values from step 2 into `admin/config.php` and save.
   (This file is only on your PC and the server, never on GitHub.)

## 4. Upload the files
The project has about 3,000 files (≈ 60 MB). InfinityFree's web File Manager only takes files up
to 10 MB, so upload with **FileZilla** (free FTP program, https://filezilla-project.org):

1. Control Panel > **FTP Details**: note *FTP Hostname*, *FTP Username*, *FTP Password* (port 21).
2. In FileZilla fill in Host / Username / Password / Port 21 and click **Quickconnect**.
3. On the right side open the **htdocs** folder and delete the default `index2.html` if it is there.
4. On the left side open the project folder, select **everything inside it** and drag it into `htdocs`
   (so you get `htdocs/index.php`, `htdocs/admin/`, `htdocs/assets/` ...). Include `admin/config.php`.
   You do not need `_backup_before_redesign/`, `problem this Projects/`, `.git/` or the `.md` files.
5. Wait until the transfer queue at the bottom is empty (can take 10-20 minutes).

## 5. Check it
1. Open your site, e.g. `https://scrapms.infinityfreeapp.com` (new accounts can take a few minutes to start).
2. Log in as the demo customer `demo@example.com` / `Demo@1234`.
3. Open `/admin/`, log in as `admin@example.com` / `ChangeMe@123`,
   then go to **Profile** and **change the admin email and password straight away**.
4. Turn on free HTTPS: Control Panel > **SSL Certificates** (if the site does not open on `https://` yet).

## Online payment (PayPal sandbox)
Payments use the PayPal **sandbox** (test money). The return links are built automatically from your
site address, so they work on the live domain without changes.
Before taking real money, `payment/success.php` must verify the payment with PayPal (right now it trusts the link).

## Updating the site later
Edit the files on your PC, test on `localhost:8000`, then upload only the changed files to `htdocs`
(and push to GitHub with `git add -A`, `git commit -m "..."`, `git push`).

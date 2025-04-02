![alt text](image.png)

## spinning-wheel-game

A simple spinning wheel game built with Laravel + Livewire uisng Sail.

### demo

Check out the demo [here](http://134.122.83.159).

```bash
Username: test@example.com
Password: password
```



### setup

1. Clone the repository
2. Rename .env.example to .env
3. Ensure Sail can start:
`docker run --rm --interactive --tty -v $(pwd):/app composer install`

4. Run `vendor/bin/sail up -d` or open in dev container
5. Install npm and build inside app directory, `npm i && npm run build`
5. Migrate & Seed `php artistan migrate --seed`

In case there are permission issues, access sail with root-shell and run `cd .. && chown -R sail:sail html`

### usage

1. Navigate to localhost
2. Login with default user `test@example.com` and `password`. (Or register new user, no email verify required.)
3. Top up balance and get on spinnin'.

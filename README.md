# Welcome to the IxDF Back-End Code Challenge!

The main goal of this challenge is to get a sense of your coding style and choices.

The code challenge does not involve any exotic or bleeding-edge technologies, tools, etc. and that’s the point: We’d like to focus on your code style and not get distracted.

On that note, we’re also not looking for "rights and wrongs", and there are no "trick parts" in this challenge.
We would merely like to get a more profound impression of how you write code.

That also allows us to have a more fruitful and constructive discussion at the technical interview. We’re not fans of white-boarding at interviews, so we’d much rather have some concrete code to talk about. We think that makes the interview much more enjoyable and productive.


## Your challenge/task

Imagine you’re our new full-stack developer 🦄 and you just got a feature request!

You’ve just stepped into the middle of a conversation on a Github issue and there was a feature request from our Design Team:
> Hey Devs!
>
> In order to improve the gamification of our course platform, and to improve our users’ engagement in the platform, we would like to implement a "Course Leaderboard". We want to make learning just as addictive as playing a computer game and need your help.
>
> The leaderboard should display a list of users who have successfully completed a course and their corresponding position at a given course. Each course must have its own slug, which follows this pattern `/courses/{slug}`, e.g. `/courses/beginners-guide-to-user-experience`). That way, every course should have a unique leaderboard URL.
>
> Here is how the leaderboard section should look like:
>
> ![image](https://user-images.githubusercontent.com/5278175/63923279-f0ef8680-ca4e-11e9-8707-4d51154ce31e.png)


## Specifications of the leaderboard

1. Display up to 9 users on the leaderboard for a given course.
2. Always display 3 users with the **highest** score of a given course (top tier).
3. Always display 3 users with the **lowest** score of a given course (bottom tier).
4. Always display currently logged-in user, surrounded by 2 other course participants (if any). When a logged-in user does not belong to the top or bottom tiers:
    1. Extend the top or bottom tier if it’s possible to create a continuous sequence of positions and not violate the rest of the specs.
    2. Create a middle tier and display logged-in user there (this is the only case when we need to display the middle tier).
5. Logged-in user *must be* in bold text (just like in the image).
6. Logged-in user *must have* higher precedence when compared to users with the same score.
7. Tiers *must be* separated by a line.

So, your goal now is to implement both back-end and front-end parts of this feature.


## What we will evaluate

There are no set-in-stone technical requirements for this feature.
The only requirement that is noticeable by our users and visitors is performance.
Leaderboard data should always be up to date (any enrolment score can be changed by a course graders/examiners/assessors anytime (there is only one method from a Grading API, see `\App\QuizAnswer::grade()`)).

You can do everything you want in order to implement this feature:
 - Change the DB structure
 - Move significant parts of the logic to the DB
 - Move significant parts of the logic to the back-end
 - Move significant parts of the logic to the front-end, or load data by AJAX (if so, please use Vanilla JS or Vue.js)
 - Move significant parts of the logic to ... okay... you get it... We don’t want to limit your ideas :)

We will be evaluating the following aspects:
 - Your ability to design the overall project architecture and keep it consistent with your implementation (note: frequent atomic commits are welcome!).
 - Your ability to write readable and reusable code with a clean API.
 - Performance of your solution (this page is pretty popular and we don’t want to overload our server)

You are of course more than welcome to ask questions about this challenge in case you’re in doubt about something or need more background information!


## How to setup a working environment

This project is a simple Laravel 8 application.

In order to help you with the initial setup we already added some basic code:
 - Routes, controllers and views
 - Migrations to create all required tables
 - Factories to create all entities
 - Database seeders to have enough information to display leaderboards


### A) Docker

This is a preferable way.

For the docker environment, we prepared a special `.env` file example: `.env.docker.example`.
In addition to that, we included a basic Docker Compose config.
So, if you are already a docker user, you simply need to execute the following commands:

```sh
# Copy the example .env file
cp .env.docker.example .env

# Build, (re)create and start containers for a service.
docker-compose up -d

# install composer dependencies
docker-compose exec app composer install

# Run all migrations and seed the DB
docker-compose exec app php artisan migrate:fresh --seed

# Generate application key
docker-compose exec app php artisan key:generate
```

If everything worked well, a project should be accessible by [http://localhost:8080](http://localhost:8080).

Got problems? Help us improve this code challenge by writing to us. We’re happy to help :-)


### B) Local/Virtual Machine

```sh
# Copy the example .env file
cp .env.example .env

# install composer dependencies
composer install

# Run all migrations and seed DB
php artisan migrate:fresh --seed

# Generate application key
php artisan key:generate
```


### After installation done

Please log in at the `/login` page by using any auto-generated email address you can find in the DB.
The password for all auto-generated users is `secret`.

After a successful login, you will be redirected to a page with a list of links to your course enrollments.
Please click any of the links, and you will be redirected to a page where you should implement your this challenge, i.e. the leaderboard.


## How to submit your solution

Please submit your solution in a git repository.
Ideally, you should make an initial commit with the files of this code challenge and then build your solution upon that.

You can submit your solution in 2 ways:
 1. Use a **private** GitHub repository and send us an invitation for [IxDF-bot](https://github.com/ixdf-bot). **Preferable way**.
 2. Use a **public** GitHub repository and send us the link.
 
PS: We at IxDF would greatly appreciate if you give us some feedback about this code-challenge :)

🦄

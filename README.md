<p align="center">
    <a href="https://github.com/HeadTriXz/Horus">
        <img src="https://user-images.githubusercontent.com/32986761/232251953-9f821ffd-5bd7-4837-9832-b38d9e2f52ea.png" alt="Logo Horus">
    </a>
</p>
<h1 align="center">Horus — A PHP Exam Tracker</h1>

<details open="open">
<summary>Table of Contents</summary>

- [About](#about)
- [Getting Started](#getting-started)
    - [Installation](#installation)
    - [Usage](#usage)
- [App Screenshots](#app-screenshots)
- [Contributing](#contributing)
- [License](#license)

</details>

---

## About

Horus is a web application developed as a university project for the course "Webtechnologie II". It is designed to serve as an exam tracking system, similar to Osiris. The application is written in modern PHP code and adheres to PSR-1 and PSR-12 standards.

The application utilizes a backend framework that includes several components such as routing, templating, access control, request handling, and dependency injection. The framework is designed to be maintainable and does not use external libraries.

## Getting Started

### Installation

> **Note**
> Composer and Node.js are only required for development.

Prerequisites:
- PHP (>= 8.2.0)
- [Composer](https://getcomposer.org/download/)
- [Node.js](https://nodejs.org/en/download)

Assuming you've already installed on your machine:
```sh
# Clone project
git clone https://github.com/HeadTriXz/Horus/
cd Horus

# Install dependencies
composer install
npm install

# Create .env file
cp .env.example .env

# Build CSS
npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --minify
```

Run the migrations:
```sh
php migrations.php --fresh
```

Run the seeders:
```sh
php migrations.php --seed
```

### Usage

Launch the server:
```sh
php -S 127.0.0.1:8000 -t public
```

To access the application, users can log in with their user ID or email. The table provided below lists all pre-registered users with their respective roles, email addresses, and user IDs. The default password for all users is `password`.

| User ID | Email                        | Role    |
|:--------|:-----------------------------|:--------|
| 100000  | l.j.knol@st.hanze.nl         | Student |
| 100001  | i.b.van.der.meer@pl.hanze.nl | Teacher |
| 100002  | j.e.molenaar@pl.hanze.nl     | Teacher |
| 100003  | t.h.mol@pl.hanze.nl          | Teacher |
| 100004  | e.van.den.heuvel@pl.hanze.nl | Teacher |
| 100005  | j.p.de.koning@pl.hanze.nl    | Teacher |
| 100006  | r.k.van.veen@pl.hanze.nl     | Teacher |
| 100007  | k.b.de.groot@pl.hanze.nl     | Teacher |
| 100008  | h.kuijpers@pl.hanze.nl       | Admin   |
| 100009  | l.jonker@pl.hanze.nl         | Admin   |

#### Permissions and roles

Horus has three roles: `Student`, `Teacher`, and `Admin`. Each role has different permissions and access levels within the application.

| Role    | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
|:--------|:------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Student | Students can view their grades and courses and enroll for exams and courses. They do not have access to modify or create any data within the application.                                                                                                                                                                                                                                                                                                                                                   |
| Teacher | Teachers can view their exams and courses and modify the grades of exams. They do not have access to modify or create any courses or users.                                                                                                                                                                                                                                                                                                                                                                 |
| Admin   | Admins have the highest level of access and can do everything that teachers can do. They can also modify and create exams, courses, and users. Admins are responsible for managing the application and ensuring that all data is accurate and up-to-date.<br/><br/>It is important to ensure that users are assigned the appropriate role based on their responsibilities and access requirements within the application. This can help to prevent unauthorized access and ensure that data remains secure. |

## App Screenshots
### Student view
| Home Page                                                                                                                                   | Grades                                                                                                                                        | Courses                                                                                                                                        | Enroll                                                                                                                                        |
|:--------------------------------------------------------------------------------------------------------------------------------------------|:----------------------------------------------------------------------------------------------------------------------------------------------|:-----------------------------------------------------------------------------------------------------------------------------------------------|:----------------------------------------------------------------------------------------------------------------------------------------------|
| <img src="https://user-images.githubusercontent.com/32986761/232252929-d9c5603e-e41b-4f77-9033-8829dd64ce00.png" title="Home" width="100%"> | <img src="https://user-images.githubusercontent.com/32986761/232252930-46ed08b3-4afa-41d5-ae95-c44a799a4c5b.png" title="Grades" width="100%"> | <img src="https://user-images.githubusercontent.com/32986761/232252931-ab5d2b6b-f180-4a9e-9bdd-1a411e982415.png" title="Courses" width="100%"> | <img src="https://user-images.githubusercontent.com/32986761/232252932-88734f82-c3e6-4675-acb9-5466fd675d15.png" title="Enroll" width="100%"> |

### Admin view
| Courses                                                                                                                                                | Exams                                                                                                                                                | Users                                                                                                                                                |
|:-------------------------------------------------------------------------------------------------------------------------------------------------------|:-----------------------------------------------------------------------------------------------------------------------------------------------------|:-----------------------------------------------------------------------------------------------------------------------------------------------------|
| <img src="https://user-images.githubusercontent.com/32986761/232252934-9dc64848-b53d-48fd-b095-25f6b1ce626a.png" title="Courses (Admin)" width="100%"> | <img src="https://user-images.githubusercontent.com/32986761/232252935-2c92238d-4ff8-4ce2-a08c-6553945a59fd.png" title="Exams (Admin)" width="100%"> | <img src="https://user-images.githubusercontent.com/32986761/232252937-400125db-edad-4ae3-bf57-606e8ad401c2.png" title="Users (Admin)" width="100%"> |

## Contributing

This library was created for a university project and is provided as-is. Contributions and improvements are welcome, but there are no current plans for further development or maintenance.

## License

This project is licensed under the **GNU General Public License v3.0**.

See [LICENSE](LICENSE) for more information.

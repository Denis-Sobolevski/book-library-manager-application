<?php
require_once "../Models/ExceptionLogger.php";

class Database
{
    private static $host;
    private static $db;
    private static $charset;
    private static $user;
    private static $pass;

    private static $exceptionLogger;

    private static $opt = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    );

    private static $connection;
    private static $obj;

    private function __construct(
        string $host = "localhost",
        string $db = "final_project",
        string $charset = "utf8",
        string $user = "root",
        string $pass = ""
    ) {
        self::$host = $host;
        self::$db = $db;
        self::$charset = $charset;
        self::$user = $user;
        self::$pass = $pass;
        self::$exceptionLogger = new ExceptionLogger("../ExceptionLogger.txt");
    }

    /**
     * Function returns a reference to an existing Singeton Database object
     * or it will initialize the Database object and will return its reference
     * @return Database a reference to the Singleton Database object
     */
    public static function GetInstance(): Database
    {
        if (self::$obj == null)
            self::$obj = new Database();

        return self::$obj;
    }

    /**
     * function tries to connect to the Database
     * and returns true or false accordingly to the result of the attempt
     * @return mixed PDO object if a connection to the database could be established, false otherwise
     */
    public function connect()
    {
        try {
            $dns = "mysql:host=" . self::$host . ";dbname=" . self::$db . ";charset=" . self::$charset;
            self::$connection = new PDO($dns, self::$user, self::$pass, self::$opt);
            return self::$connection;
        } catch (Exception $e) {
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : connect");
            return false;
        }
    }


    private function disconnect()
    {
        self::$connection = null;
    }

    #region User table activities:
    /**
     * Function will return a boolean answer if a user with the given email alredy exists
     * @param string $email an email address of a user
     * @return bool returns true if the given email address is associated with an user, false otherwise
     */
    public function isExistUserByEmail(string $email): bool
    {
        try {
            $this->connect();
            $statement = self::$connection->prepare("SELECT COUNT(*) AS C
                                                    FROM user
                                                    WHERE email = :email");
            $statement->execute([':email' => $email]);
            $count = $statement->fetch(PDO::FETCH_ASSOC);
            $this->disconnect();

            return boolval($count['C']);
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : isExistUserByEmail");
            return false;
        }
    }


    /**
     * Function recieves a User object, will try to register
     * the user with an encrypted password and add it to the user table
     * @param User $user User object
     * @return bool returns true if the task was successful, false otherwise
     */
    public function registerUserWithEncryptedPassword(User $user): bool
    {
        try {
            $this->connect();
            $user->setPassword(password_hash($user->getPassword(), PASSWORD_DEFAULT));

            $statement = self::$connection->prepare(
                "INSERT INTO user VALUES (
				:email,
                :type,
				:firstName,
				:lastName,
				:password)"
            );

            $parameters = [
                ':email'     => $user->getEmail(),
                ':type'      => 0,
                ':firstName' => $user->getFirstName(),
                ':lastName'  => $user->getLastName(),
                ':password'  => $user->getPassword()
            ];
            $statement->execute($parameters);
            $this->disconnect();

            // we can return that the task was successful:
            return true;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : registerUserWithEncryptedPassword");
            return false;
        }
    }

    /**
     * Function recieves an email and password from a user
     * will check if the email is associated with any user
     * and will check if the password is correct, if so it will return true
     * else it will return false
     * @param string $email the email address of the user
     * @param string $password the password of the user
     * @return bool returns true if the task was successful,else will
     * display a message as to why the task failed and will return false
     */
    public function login_user(string $email, string $password): bool
    {
        // check if the given password is correct for 
        // the user that is trying to login
        try {
            $this->connect();

            $statement = self::$connection->prepare(
                "SELECT user.PASSWORD AS password
                     FROM user
                     WHERE user.email = :email"
            );

            $statement->execute([':email' => $email]);
            // we fetch the users password from the database:
            $passwordToCheck = $statement->fetch(PDO::FETCH_ASSOC)['password'];
            $this->disconnect();

            // we will try to verify if the user
            // has entered the right password and we will return a boolean result:
            if (password_verify($password, $passwordToCheck))
                return true;

            // password does not match:
            return false;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : login_user");
            return false;
        }
    }

    /**
     * function recieves an email address associated with an account
     * will try to create and return an User object representing
     * use with the associated email address
     * @param string $email the email address of the user
     * @return User an User oject of the user associated with the given email address
     */
    public function getUserByEmail(string $email): User
    {
        // user does not exist
        if (!$this->isExistUserByEmail($email)) {
            return null;
        } else {
            // the email is associated with a user
            // we will try to fetch the user object:
            try {
                $this->connect();
                $statement = self::$connection->prepare(
                    "SELECT *
                    FROM user
                    WHERE user.email = :email"
                );
                $statement->execute([':email' => $email]);
                $user = $statement->fetchObject('User');
                $this->disconnect();

                return $user;
            } catch (Exception $e) {
                $this->disconnect();
                self::$exceptionLogger->logException($e->getMessage(), "Database.php : getUserByEmail");
                return null;
            }
        }
    }
    #endregion

    #region Book table activities:
    /**
     * Function will return a boolean answer if a book with the given name and author alredy exists
     * @param string $bookName the name of the book to check
     * @param string $author author's name of the given book
     * @return bool returns true if the given book is already exists, false otherwise
     */
    public function isExistBook(string $bookName, string $author): bool
    {
        try {
            $this->connect();
            $statement = self::$connection->prepare("SELECT COUNT(*) AS C
                                                    FROM book
                                                    WHERE bookName = :bookName
                                                    AND author = :author");
            $statement->execute([':bookName' => $bookName, ':author' => $author]);
            $count = $statement->fetch(PDO::FETCH_ASSOC);
            $this->disconnect();

            return boolval($count['C']);
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : isExistBook");
            return false;
        }
    }


    /**
     * this function recieves a new Book object, will try to add the
     * Book to the book table and will return true or false accordingly
     * @param Book $book a new Book object
     * @return bool true if the Book object was added to the book table, false otherwise
     */
    public function addBook(Book $book): bool
    {
        try {
            $this->connect();

            $statement = self::$connection->prepare(
                "INSERT INTO book VALUES(
                 :bookName,
                 :author,
                 :year)"
            );

            $parameters = [
                ':bookName' => $book->getBookName(),
                ':author'   => $book->getAuthor(),
                ':year'     => $book->getYear()
            ];

            $statement->execute($parameters);
            $this->disconnect();

            return true;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : addBook");
            return false;
        }
    }

    /**
     * function will return an array of all the books that exists inside the library
     * @return array an array of all the books as objects
     */
    public function getAllBooks(): array
    {
        try {
            $this->connect();
            $books = [];

            if (self::$connection !== null) {
                $result = self::$connection->query(
                    "SELECT * FROM book"
                );

                while ($book = $result->fetchObject('Book'))
                    $books[] = $book;
            }
            $this->disconnect();

            return $books;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : getAllBooks");
            return [];
        }
    }

    /**
     * Function receives the book's name and 
     * its author name and try to remove the book from the database
     * only if there is no user that is currently borrowing the book
     * @param string $bookName name of the book
     * @param string $author name of the author of the book
     * @return boolean true if the book was successfully removed, false otherwise
     */
    function removeBook(string $bookName, string $author): bool
    {
        try {
            $this->connect();
            $statement = self::$connection->prepare(
                "DELETE FROM book
                WHERE bookName = :bookName
                AND author = :author"
            );

            $result = $statement->execute([
                ':bookName' => $bookName,
                ':author'  => $author
            ]);

            $this->disconnect();
            return true;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : removeBook");
            return false;
        }
    }


    /**
     * function will return an array of all the books that are available and free to be borrowed
     * by the user
     * @return array an array of all the available to borrow books as objects
     */
    function getAllAvailableBooksToBorrow(): array
    {
        try {
            $this->connect();
            $books = [];

            if (self::$connection !== null) {
                // select only the books from book table
                // where its not in the user_book table (the book is not borrowed by anybody):
                $result = self::$connection->query(
                    "SELECT *
                     FROM book
                     WHERE NOT EXISTS(SELECT user_book.bookName, user_book.author
                                      FROM user_book
                                      WHERE book.bookName = user_book.bookName AND book.author = user_book.author)"
                );

                while ($book = $result->fetchObject('Book'))
                    $books[] = $book;
            }
            $this->disconnect();

            return $books;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : getAllAvailableBooksToBorrow");
            return [];
        }
    }
    #endregion

    #region user_book table activites:
    /**
     * this function recieves a books name and its authors name
     * will return true if the book is currently borrowed by somebody, otherwise return false
     * @param string $bookName name of the book to check
     * @param string $author the author of the book to check
     * @return boolean true if the book is currently borrowed by somebody, otherwise returns false
     */
    function isBorrowed(string $bookName, string $author): bool
    {
        try {
            $this->connect();
            $statement = self::$connection->prepare("SELECT COUNT(*) AS C
                                                     FROM user_book
                                                     WHERE bookName = :bookName
                                                     AND author = :author");
            $statement->execute([':bookName' => $bookName, ':author' => $author]);
            $count = $statement->fetch(PDO::FETCH_ASSOC);
            $this->disconnect();

            return boolval($count['C']);
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : isBorrowed");
            return false;
        }
    }

    /**
     * function recieves the email of the user, the book name to borrow and its author name
     * will insert a new row into the user_book table so it represents that
     * the user is now borrowing the book
     * @param string $userEmail email of the user that is trying to borrow the book
     * @param string $bookName name of the book to borrow
     * @param string $author author's name of the book to borrow
     * @return boolean returns true if use has successfully borrowed the book, otherwise returns false
     */
    function borrowBook(string $userEmail, string $bookName, string $author): bool
    {
        try {
            $this->connect();

            $statement = self::$connection->prepare(
                "INSERT INTO user_book VALUES(
                                 :email,
                                 :bookName,
                                 :author)"
            );

            $parameters = [
                ':email' => $userEmail,
                ':bookName'  => $bookName,
                ':author'    => $author,
            ];

            $statement->execute($parameters);
            $this->disconnect();

            return true;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : borrowBook");
            return false;
        }
    }

    /**
     * function recieves an email of a user
     * will return an array of book objects that the current user is borrowing right now
     * @param string $userEmail the current user email address
     * @return array an array of book objects that the current user is borrowing right now
     */
    function getAllCurrentUserBorrowedBooks(string $userEmail): array
    {
        try {
            $this->connect();
            $books = [];

            if (self::$connection !== null) {
                // select only the books from book table
                // where its not in the user_book table
                $statement = self::$connection->prepare(
                    "SELECT book.*
                    FROM book NATURAL JOIN user_book
                    where email = :email"
                );

                $statement->execute([':email' => $userEmail]);
                $this->disconnect();

                while ($book = $statement->fetchObject('Book')) {
                    $books[] = $book;
                }
            }
            $this->disconnect();

            return $books;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : getAllCurrentUserBorrowedBooks");
            return [];
        }
    }

    /**
     * function recieves the book name and its author name
     * will try to remove it from the user_book table
     * @param string $bookName name of the book to retrieve
     * @param string $author the book author name
     * @return boolean true if the book was successfully retrieved, false otherwise
     */
    function retrieveBorrowedBook(string $bookName, string $author): bool
    {
        try {
            $this->connect();
            $statement = self::$connection->prepare(
                "DELETE FROM user_book
                 WHERE bookName = :bookName
                 AND author = :author"
            );

            $result = $statement->execute([
                ':bookName' => $bookName,
                ':author'  => $author
            ]);

            $this->disconnect();
            return true;
        } catch (Exception $e) {
            $this->disconnect();
            self::$exceptionLogger->logException($e->getMessage(), "Database.php : retrieveBorrowedBook");
            return false;
        }
    }
    #endregion


}

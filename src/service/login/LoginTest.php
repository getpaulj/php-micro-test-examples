<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;


class User {
    public  $loginName;
    public  $password;

    public function __construct(string $loginName, string $password = NULL){
        $this->loginName = $loginName;
        $this->password = $password;
    }
}

interface UserRepository {
    function get($id): User;
    function save(User $user);
}

class Login {
    public $when;
    public $user;
}

interface LoginRepository {
    function get($id): User;
    function save(Login $user): Login;
    function findByEmail(Login $user): Login;
}


interface LoginFactory {
    function create(User $user): Login;
}

interface PasswordValidator {
    function validate(User $user, string $password): bool;
}


class LoginService
{
    private $userRepo;
    private $loginFactory;
    private $loginRepo;
    private $passwordValidator;

    public function __construct(UserRepository $userRepo, LoginRepository $loginRepo, LoginFactory $loginFactory, PasswordValidator $passwordValidator)
    {
        $this->userRepo = $userRepo;
        $this->loginRepo = $loginRepo;
        $this->loginFactory = $loginFactory;
        $this->passwordValidator = $passwordValidator;
    }

    public function login($loginName, $password): ?Login
    {
        $user = $this->userRepo->get($loginName);

        $isValid = $this->passwordValidator->validate($user, $password);
        if (!$isValid) {
            return NULL;
        }
        $login = $this->loginFactory->create($user);
        return $this->loginRepo->save($login);
    }
}



class LoginTest extends TestCase {

    private  $password = 'somePassword';
    private  $userName = 'someUserName';

    private $userMock;
    private $loginRepoMock;
    private $loginFactoryMock;
    private $userRepoMock;
    private $passwordValidatorMock;

    private $loginService;


    protected function setUp(): void{

        $this->userMock =  $this->createMock(User::class);
        $this->loginRepoMock =  $this->createMock(LoginRepository::class);
        $this->loginFactoryMock =  $this->createMock(LoginFactory::class);
        $this->userRepoMock =  $this->createMock(UserRepository::class);
        $this->passwordValidatorMock =  $this->createMock(PasswordValidator::class);

        $this->loginService = new LoginService(
            $this->userRepoMock,
            $this->loginRepoMock,
            $this->loginFactoryMock,
            $this->passwordValidatorMock
        );
    }

    public function testWhenPasswordNotOk()
    {
        // given
        $this->userRepoMock->expects($this->once())->method('get')->with($this->userName)->willReturn($this->userMock);
        $this->passwordValidatorMock->expects($this->once())->method('validate')->with($this->userMock, $this->password)->willReturn(false);

        // when
        $actualLogin = $this->loginService->login($this->userName, $this->password);

        //then
        $this->assertEquals(NULL, $actualLogin);
    }


    public function testSuccessfulLogin()
    {
        // given
        $expectedLogin = $this->createMock(Login::class);
        $this->userRepoMock->expects($this->once())->method('get')->with($this->userName)->willReturn($this->userMock);
        $this->loginFactoryMock->expects($this->once())->method('create')->with($this->userMock)->willReturn($expectedLogin);
        $this->loginRepoMock->expects($this->once())->method('save')->with($expectedLogin)->willReturn($expectedLogin);
        $this->passwordValidatorMock->expects($this->once())->method('validate')->with($this->userMock, $this->password)->willReturn(true);

        // when
        $actualLogin = $this->loginService->login($this->userName,$this->password);

        //then
        $this->assertEquals($expectedLogin, $actualLogin);
    }
}




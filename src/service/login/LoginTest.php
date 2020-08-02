<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;


class User {
    public  $loginName;
    public  $password;

    public function __construct(string $loginName, string $password){
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
}


interface LoginFactory {
    function create(User $user): Login;
}


class LoginService
{
    private $userRepo;
    private $loginFactory;
    private $loginRepo;

    public function __construct(UserRepository $userRepo, LoginRepository $loginRepo, LoginFactory $loginFactory)
    {
        $this->userRepo = $userRepo;
        $this->loginRepo = $loginRepo;
        $this->loginFactory = $loginFactory;
    }

    public function login($loginName): Login
    {
        $user = $this->userRepo->get($loginName);
        $login = $this->loginFactory->create(new User('d', 'b'));
        return $this->loginRepo->save($login);
    }
}



class LoginTest extends TestCase {

    public function testSuccessfulLogin()
    {
        // given
        $expectedUser = new User('someName', 'somePassword');
        $expectedLogin = $this->createMock(Login::class);

        $loginRepoMock =  $this->createMock(LoginRepository::class);
        $loginFactoryMock =  $this->createMock(LoginFactory::class);
        $userRepoMock =  $this->createMock(UserRepository::class);

        $loginService = new LoginService(
            $userRepoMock,
            $loginRepoMock,
            $loginFactoryMock
        );
        $userRepoMock->expects($this->once())->method('get')->with($expectedUser->loginName)->willReturn($expectedUser);
        $loginFactoryMock->expects($this->once())->method('create')->with($expectedUser)->willReturn($expectedLogin);
        $loginRepoMock->expects($this->once())->method('save')->with($expectedLogin)->willReturn($expectedLogin);

        // when
        $actualLogin = $loginService->login($expectedUser->loginName);

        //then
        $this->assertEquals($expectedLogin, $actualLogin);
    }
}




<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Post;
use App\Factory\CommentFactory;
use App\Factory\PostFactory;
use App\Factory\TagFactory;
use App\Factory\UserFactory;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var UserFactory
     */
    private $userFactory;

    /**
     * @var TagFactory
     */
    private $tagFactory;

    /**
     * @var PostFactory
     */
    private $postFactory;

    /**
     * @var CommentFactory
     */
    private $commentFactory;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * @var PostRepository
     */
    private $postRepository;

    /**
     * @var array
     */
    private $activatedUsers = [];

    /**
     * @var array
     */
    private $publishedPosts = [];

    /**
     * @var array
     */
    private $tagsForPost = [];

    /**
     * @var string
     */
    const DEFAULT_PASSWORD = "1234";


    public function __construct(
        UserFactory $userFactory,
        TagFactory $tagFactory,
        PostFactory $postFactory,
        CommentFactory $commentFactory,
        UserRepository $userRepository,
        TagRepository $tagRepository,
        PostRepository $postRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->userFactory = $userFactory;
        $this->tagFactory = $tagFactory;
        $this->postFactory = $postFactory;
        $this->userRepository = $userRepository;
        $this->tagRepository = $tagRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->postRepository = $postRepository;
        $this->commentFactory = $commentFactory;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        //$this->createUsers();
        $this->createTags();
        //$this->createPosts();
        //$this->createComments();
    }

    /**
     * Create test users
     * @throws \Exception
     */
    private function createUsers(): void
    {
        for ($i = 20; $i < 40; $i++) {
            $user = $this->userFactory->createNew();
            $user->setName("user_$i")
                ->setPassword(
                    $this->passwordEncoder->encodePassword($user, static::DEFAULT_PASSWORD)
                )
                ->setEmail("email$i@gmail.com")
            ;

            if ($i % 6 === 0) {
                $user->setStatus(User::STATUS_BLOCKED);
            }

            if ($i % 2 === 0) {
                $user->setStatus(User::STATUS_ACTIVE);
            }

            $this->manager->persist($user);
        }

        $this->manager->flush();
    }

    /**
     * Create test tags
     */
    private function createTags(): void
    {
        $tag = $this->tagFactory->createNew();
        $tag->setName('PHP');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('JS');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('HTML');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('TESTS');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('GO');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('GO1');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('GO2');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('PYTHON');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('PYTHON-SIMILAR-1');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('PYTHON-SIMILAR-2');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('PYTHON-SIMILAR-3');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('JAVA');
        $this->manager->persist($tag);

        $tag = $this->tagFactory->createNew();
        $tag->setName('JAVA-SIMILAR-1');
        $this->manager->persist($tag);

        $this->manager->flush();
    }

    /**
     * @throws \Exception
     */
    private function createPosts(): void
    {
        $post = $this->createRandomPost();
        $post->setTitle('И такое бывает')
            ->setContent(<<<EOF
<p>Сегодня в метро</p>
<p>Сразу поясню я на инвалидной коляске</p>
<p>Еду в вагоне. Подходит паренёк лет 13-14 и спрашивает</p>
<p>- До какой станции едете?</p>
<p>- А тебе зачем?</p>
<p>- Помочь выйти.</p>
<p>- Спасибо, я до Лесной.</p>
<p>Следующая станция Лесная</p>
<p>Мальчишка предупредил пассажиров у дверей, что я буду выходить. Помог выехать из вагона. Мобильной службы не оказалось.</p>
<p>- Спасибо тебе огромное, я дождусь службу.</p>
<p>- Я помогу доехать до эскалатора.</p>
<p>-Хорошо, тогда подвезти меня к будке.</p>
<p>- Тут много народа я оставлю вас в стороне, а сам подойду.</p>
<p>И подошёл, и постучал, и показал барышне в будке на меня. И стоял рядом со мной пока ребята из мобильной службы пристёгивали меня к Тэми. Потом продолжил свой путь дальше на следующей электричке, а меня поднимали наверх.</p>
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Как убрать укус комара за 20 секунд')
            ->setContent(<<<EOF
<p>Сегодня прочла тут длинное обсуждение на тему того, что делать с укусами комаров. Кто мазь советовал, кто таблетки, кто антигистаминный гель... Но это ж сколько всего надо найти, докупить и взять с собой!</p>
<p>Хочу поделиться лайфхаком от моего деда. Как убрать укус комара за 20 секунд ПОЛНОСТЬЮ и насовсем, без зуда, без опухания, и главное - ничего не надо покупать, носить с собой и тд.</p>
<p>Вот вас укусило насекомое, любое, волдырь чешется... Берем чашку, наливаем в нее кипяток. Можно заварить чай или кофе попутно. Кладем туда металлическую ложку и ждем, пока она нагреется до состояния "неберучка". Вам нужна такая температура металла, чтобы на грани терпения, но все же можно было держать ложку в пальцах. Не раскаленная, а просто очень горячая, чтобы не было ожога.</p>
<p>И прислоняем разогретым концом к укусу. Ждем 15-20 секунд, чтобы укус прогрелся на максималках.</p>
<p>Готово, вы восхитительны.</p>
<p>Больше он не будет чесаться, потому что чешется слюна насекомого, вызывающая аллергию и реакцию кожи, а она под действием тепла распадается. Укус перестает чесаться мгновенно, на коже остается маленькая красная точка.</p>
<p>Для особо ленивых - можно точечно прислонить и край чашки, и любой прогретый предмет.</p>
<p>Бывает так, что укусил кто-то особо токсичный (клоп, блоха), тогда операцию придется повторить через часов 8, потому что укус снова начнет немного чесаться. Но, опять же, никаких лишних покупок и телодвижений</p>
<br />
<p>Всем спокойных ночей без кровопийц :)</p>
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Keanu reeves cyberpunk 2077')
            ->setContent(<<<EOF
img src="https://cv1.pikabu.ru/video/2019/06/10/1560127041252210064_460x258.jpg" />
<p>Чувство от нахождения там, от хождения по улицам будущего, от этого будет реально захватывать дух (breathtaking - захватывает дух / потрясающий ) !</p>
<p>крик из толпы: Это ты потрясающий !</p>
<p>Это ты потрясающий !</p>
<p>Вы все потрясающие</p>
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Как я память продавал...')
            ->setContent(<<<EOF
<p>Ага. Продавал как то серверную память. Долго продавал... Т.к. планка специфическая и в обычные ПК не лезет. В итоге нарисовался покупатель, я безумно рад, т.к. с финансами конкретный напряжище. Договорились, встретились. Там мужик такой лет под 40. Пообщались в каких режимах, на каких частотах память работает, какие материнки любит, какие не очень. Он еще вскользь задал пару технических вопросов по серверам и их обслуживанию и в конце говорит, мол а ты не хотел бы сменить работу?  Оказалось, это директор небольшой фирмы. У них накрылась планка памяти в старом серваке и найти такую они смогли только по БУ объявам. Фирма небольшая 15 человек. ЗП предложил выше, чем на текущем месте.  Как итог, я проработал у них почти 2 года, и когда уходил фирма насчитывала более 100 человек.  Один из лучших директоров на моей памяти.</p>
<p>Никогда не думал, что продавая вещь, можно получить новую работу))</p>
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Когда не умеешь работать в фотошопе')
            ->setContent(<<<EOF
<img src="https://cs11.pikabu.ru/post_img/big/2019/06/09/7/1560076995154625248.jpg" />
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Про учёбу :)')
            ->setContent(<<<EOF
<img src="https://cs7.pikabu.ru/post_img/2019/06/09/10/1560100066177071842.jpg" />
EOF
            )
        ;
        $this->manager->persist($post);

        // NEXT POST

        $post = $this->createRandomPost();
        $post->setTitle('Про учёбу :)')
            ->setContent(<<<EOF
<img src="https://cs7.pikabu.ru/post_img/2019/06/09/10/1560100066177071842.jpg" />
EOF
            )
        ;
        $this->manager->persist($post);

        $this->manager->flush();
    }

    /**
     * @return Post
     * @throws \Exception
     */
    private function createRandomPost()
    {
        $post = $this->postFactory->createNew();
        $post->setAuthor($this->getRandomAuthor())
            ->setTags($this->getRandomTags())
            ->setRating($this->getRandomRating())
            ->setStatus($this->getRandomStatus())
        ;

        return $post;
    }

    /**
     * @return User|null
     * @throws \Exception
     */
    private function getRandomAuthor(): ?User
    {
        if (empty($this->activatedUsers)) {
            $this->activatedUsers = $this->userRepository->findAllActivatedUsers();
        }

        if (empty($this->activatedUsers)) {
            throw new \Exception('No activated users presented');
        }

        $index = array_rand($this->activatedUsers);

        return $this->activatedUsers[$index];
    }

    /**
     * @return array
     */
    private function getRandomTags()
    {
        $tags = [];

        while (true) {
            $tag = $this->getRandomTag();

            if ($tag === null) {
                break;
            }

            if (in_array($tag, $tags)) {
                break;
            }

            $tags[] = $tag;

            if (count($tags) >= 2) {
                break;
            }
        }

        return $tags;
    }

    /**
     * @return \App\Entity\Tag|mixed|null
     */
    private function getRandomTag()
    {
        if (empty($this->tagsForPost)) {
            $this->tagsForPost = $this->tagRepository->findAll();
        }

        if (empty($this->tagsForPost)) {
            return null;
        }

        $index = array_rand($this->tagsForPost);

        return $this->tagsForPost[$index];
    }

    /**
     * @return int
     * @throws \Exception
     */
    private function getRandomRating()
    {
        return random_int(-1000, 1000);
    }

    /**
     * @return mixed
     */
    private function getRandomStatus()
    {
        $statuses = [
            Post::STATUS_DRAFT,
            Post::STATUS_MODERATION_CHECK,
            Post::STATUS_PUBLISHED,
            Post::STATUS_DECLINED,
        ];

        $index = array_rand($statuses);

        return $statuses[$index];
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    private function getRandomPublishedPost()
    {
        if (empty($this->publishedPosts)) {
            $this->publishedPosts = $this->postRepository->findPublishedPosts();
        }

        if (empty($this->publishedPosts)) {
            throw new \Exception('No published posts');
        }

        $index = array_rand($this->publishedPosts);

        return $this->publishedPosts[$index];
    }

    /**
     * @throws \Exception
     */
    public function createComments()
    {
        for ($i = 0; $i < 10; $i++) {
            $comment = $this->commentFactory->createNew();
            $comment->setAuthor($this->getRandomAuthor())
                ->setPost($this->getRandomPublishedPost())
                ->setText($this->getRandomText())
            ;
            $this->manager->persist($comment);
        }

        $this->manager->flush();
    }

    /**
     * @return mixed
     */
    private function getRandomText()
    {
        $texts = [
            'Awesome, best post!',
            'Maybe you can do something more better?',
            'Cool, any continue ?',
            'Точно по теме пост?',
            'Когда уже будет что-то интересное?',
            'Можно я сегодня уйду пораньше?',
        ];
        $index = array_rand($texts);

        return $texts[$index];
    }
}

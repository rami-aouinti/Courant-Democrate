<?php

declare(strict_types=1);

namespace App\User\Infrastructure\DataFixtures\ORM;

use App\Article\Domain\Entity\Comment;
use App\Article\Domain\Entity\Post;
use App\Article\Domain\Entity\Tag;
use App\Event\Domain\Entity\Event;
use App\General\Domain\Enum\Locale;
use App\General\Domain\Rest\UuidHelper;
use App\Role\Application\Security\Interfaces\RolesServiceInterface;
use App\Setting\Domain\Entity\Component;
use App\Setting\Domain\Entity\Menu;
use App\Setting\Domain\Entity\Setting;
use App\Tests\Utils\PhpUnitUtil;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserGroup;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\String\AbstractUnicodeString;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\String\u;
use Throwable;

use function array_map;


/**
 * Class LoadUserData
 *
 * @package App\User
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
#[AutoconfigureTag('doctrine.fixture.orm')]
final class LoadUserData extends Fixture implements OrderedFixtureInterface
{
    /**
     * @var array<string, string>
     */
    public static array $uuids = [
        'john' => '20000000-0000-1000-8000-000000000001',
        'john-logged' => '20000000-0000-1000-8000-000000000002',
        'john-api' => '20000000-0000-1000-8000-000000000003',
        'john-user' => '20000000-0000-1000-8000-000000000004',
        'john-admin' => '20000000-0000-1000-8000-000000000005',
        'john-root' => '20000000-0000-1000-8000-000000000006',
    ];

    public function __construct(
        private readonly RolesServiceInterface $rolesService,
        private readonly SluggerInterface $slugger
    ) {
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @throws Throwable
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadTags($manager);
        // Create entities
        array_map(
            fn (?string $role): bool => $this->createUser($manager, $role),
            [
                null,
                ...$this->rolesService->getRoles(),
            ],
        );

        // Flush database changes
        $manager->flush();
    }

    /**
     * Get the order of this fixture
     */
    public function getOrder(): int
    {
        return 3;
    }

    public static function getUuidByKey(string $key): string
    {
        return self::$uuids[$key];
    }

    /**
     * Method to create User entity with specified role.
     *
     * @throws Throwable
     */
    private function createUser(ObjectManager $manager, ?string $role = null): bool
    {
        $suffix = $role === null ? '' : '-' . $this->rolesService->getShort($role);
        // Create new entity
        $entity = (new User())
            ->setUsername('john' . $suffix)
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setLocale(Locale::FI)
            ->setEmail('john.doe' . $suffix . '@test.com')
            ->setPlainPassword('password' . $suffix);

        if ($role !== null) {
            /** @var UserGroup $userGroup */
            $userGroup = $this->getReference('UserGroup-' . $this->rolesService->getShort($role));
            $entity->addUserGroup($userGroup);
        }

        PhpUnitUtil::setProperty(
            'id',
            UuidHelper::fromString(self::$uuids['john' . $suffix]),
            $entity
        );

        $settingEntity = $this->addSetting();
        $settingEntity->setUser($entity);
        $settingEntity->setCreatedBy($entity);
        $settingEntity->setUpdatedBy($entity);

        $entity->setSetting($settingEntity);


        for ($i = 0; $i<10 ; $i++) {
            $entity->addMenu($this->getMenus($i));
            $manager->persist($this->getMenus($i));
        }

        for ($i = 0; $i<10 ; $i++) {
            $manager->persist($this->getComponents($i));
            $entity->addComponent($this->getComponents($i));
        }

        $currentDate = new \DateTime('now');
        $tomorrowDate = new \DateTime('tomorrow');
        for ($i = 0; $i<10 ; $i++) {

            $event = new Event();
            $event->setTitle('event_test' . $i);
            $event->setDescription('event_description' . $i);
            $event->setBackgroundColor('red');
            $event->setBorderColor('black');
            $event->setTextColor('white');
            $event->setStart($currentDate);
            $event->setEnd($tomorrowDate);
            $event->setClassName('bg-gradient-danger');
            $event->setUser($entity);
            $manager->persist($event);
            $entity->addEvent($event);
        }

        // Persist entity
        $manager->persist($settingEntity);
        $manager->persist($entity);
        // Create reference for later usage
        $this->addReference('User-' . $entity->getUsername(), $entity);

        $this->addPosts($manager, $entity);

        return true;
    }

    private function addSetting(): Setting
    {
        $settingEntity = new Setting();
        $settingEntity->setSiteName("Courant Democrate");
        $settingEntity->setDrawer(true);
        $settingEntity->setSidebarColor("warning");
        $settingEntity->setSidebarTheme("transparent");
        return $settingEntity;
    }

    /**
     * @throws \Exception
     */
    private function addPosts($manager, $entity): void
    {
        $this->loadPosts($manager, $entity);
    }

    private function loadTags(ObjectManager $manager): void
    {
        foreach ($this->getTagData() as $name) {
            $tag = new Tag();
            $tag->setName($name);

            $manager->persist($tag);
            $this->addReference('tag-'.$name, $tag);
        }

        $manager->flush();
    }

    /**
     * @return string[]
     */
    private function getTagData(): array
    {
        return [
            'lorem',
            'ipsum',
            'consectetur',
            'adipiscing',
            'incididunt',
            'labore',
            'voluptate',
            'dolore',
            'pariatur',
        ];
    }

    /**
     * @throws \Exception
     */
    private function loadPosts(ObjectManager $manager, $author): void
    {
        foreach ($this->getPostData() as [$title, $slug, $summary, $content, $publishedAt, $tags]) {
            $post = new Post();
            $post->setTitle($title);
            $post->setSlug((string)$slug);
            $post->setSummary((string)$summary);
            $post->setContent($content);
            $post->setPublishedAt($publishedAt);
            $post->setAuthor($author);
            $post->addTag(...$tags);
            $post->setCreatedBy($author);
            $post->setUpdatedBy($author);

            foreach (range(1, 5) as $i) {
                /** @var User $commentAuthor */

                $comment = new Comment();
                $comment->setAuthor($author);
                $comment->setContent((string)$this->getRandomText(random_int(255, 512)));
                $comment->setPublishedAt(new DateTime('now + '.$i.'seconds'));

                $post->addComment($comment);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }


    /**
     * @return array<int, array{0: string, 1: AbstractUnicodeString, 2: string, 3: string, 4: DateTime, 5: User, 6: array<Tag>}>
     *@throws \Exception
     *
     */
    private function getPostData(): array
    {
        $posts = [];
        foreach ($this->getPhrases() as $i => $title) {
            // $postData = [$title, $slug, $summary, $content, $publishedAt, $tags, $comments];

            $posts[] = [
                $title,
                $this->slugger->slug($title)->lower(),
                $this->getRandomText(),
                $this->getPostContent(),
                (new DateTime('now - '.$i.'days'))->setTime(random_int(8, 17), random_int(7, 49), random_int(0, 59)),
                // Ensure that the first post is written by Jane Doe to simplify tests
                $this->getRandomTags(),
            ];
        }

        return $posts;
    }

    /**
     * @return string[]
     */
    private function getPhrases(): array
    {
        return [
            'Lorem ipsum dolor sit amet consectetur adipiscing elit',
            'Pellentesque vitae velit ex',
            'Mauris dapibus risus quis suscipit vulputate',
            'Eros diam egestas libero eu vulputate risus',
            'In hac habitasse platea dictumst',
            'Morbi tempus commodo mattis',
            'Ut suscipit posuere justo at vulputate',
            'Ut eleifend mauris et risus ultrices egestas',
            'Aliquam sodales odio id eleifend tristique',
            'Urna nisl sollicitudin id varius orci quam id turpis',
            'Nulla porta lobortis ligula vel egestas',
            'Curabitur aliquam euismod dolor non ornare',
            'Sed varius a risus eget aliquam',
            'Nunc viverra elit ac laoreet suscipit',
            'Pellentesque et sapien pulvinar consectetur',
            'Ubi est barbatus nix',
            'Abnobas sunt hilotaes de placidus vita',
            'Ubi est audax amicitia',
            'Eposs sunt solems de superbus fortis',
            'Vae humani generis',
            'Diatrias tolerare tanquam noster caesium',
            'Teres talis saepe tractare de camerarius flavum sensorem',
            'Silva de secundus galatae demitto quadra',
            'Sunt accentores vitare salvus flavum parses',
            'Potus sensim ad ferox abnoba',
            'Sunt seculaes transferre talis camerarius fluctuies',
            'Era brevis ratione est',
            'Sunt torquises imitari velox mirabilis medicinaes',
            'Mineralis persuadere omnes finises desiderium',
            'Bassus fatalis classiss virtualiter transferre de flavum',
        ];
    }

    private function getRandomText(int $maxLength = 255): \Symfony\Component\String\UnicodeString|string
    {
        $phrases = $this->getPhrases();
        shuffle($phrases);

        do {
            $text = u('. ')->join($phrases)->append('.');
            array_pop($phrases);
        } while ($text->length() > $maxLength);

        return $text;
    }

    private function getPostContent(): string
    {
        return <<<'MARKDOWN'
            Lorem ipsum dolor sit amet consectetur adipisicing elit, sed do eiusmod tempor
            incididunt ut labore et **dolore magna aliqua**: Duis aute irure dolor in
            reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia
            deserunt mollit anim id est laborum.

              * Ut enim ad minim veniam
              * Quis nostrud exercitation *ullamco laboris*
              * Nisi ut aliquip ex ea commodo consequat

            Praesent id fermentum lorem. Ut est lorem, fringilla at accumsan nec, euismod at
            nunc. Aenean mattis sollicitudin mattis. Nullam pulvinar vestibulum bibendum.
            Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos
            himenaeos. Fusce nulla purus, gravida ac interdum ut, blandit eget ex. Duis a
            luctus dolor.

            Integer auctor massa maximus nulla scelerisque accumsan. *Aliquam ac malesuada*
            ex. Pellentesque tortor magna, vulputate eu vulputate ut, venenatis ac lectus.
            Praesent ut lacinia sem. Mauris a lectus eget felis mollis feugiat. Quisque
            efficitur, mi ut semper pulvinar, urna urna blandit massa, eget tincidunt augue
            nulla vitae est.

            Ut posuere aliquet tincidunt. Aliquam erat volutpat. **Class aptent taciti**
            sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Morbi
            arcu orci, gravida eget aliquam eu, suscipit et ante. Morbi vulputate metus vel
            ipsum finibus, ut dapibus massa feugiat. Vestibulum vel lobortis libero. Sed
            tincidunt tellus et viverra scelerisque. Pellentesque tincidunt cursus felis.
            Sed in egestas erat.

            Aliquam pulvinar interdum massa, vel ullamcorper ante consectetur eu. Vestibulum
            lacinia ac enim vel placerat. Integer pulvinar magna nec dui malesuada, nec
            congue nisl dictum. Donec mollis nisl tortor, at congue erat consequat a. Nam
            tempus elit porta, blandit elit vel, viverra lorem. Sed sit amet tellus
            tincidunt, faucibus nisl in, aliquet libero.
            MARKDOWN;
    }

    /**
     * @throws \Exception
     *
     * @return array<Tag>
     */
    private function getRandomTags(): array
    {
        $tagNames = $this->getTagData();
        shuffle($tagNames);
        $selectedTags = \array_slice($tagNames, 0, random_int(2, 4));

        return array_map(function ($tagName) {
            /** @var Tag $tag */
            $tag = $this->getReference('tag-'.$tagName);

            return $tag;
        }, $selectedTags);
    }


    /**
     * @param $i
     * @return Menu
     */
    private function getMenus($i)
    {
        $menu = new Menu();
        $menu->setMenuName("Dashboard"  . $i);
        $menu->setMenuPath("/dashboard");
        $menu->setMenuIcon("dashboard");


        return $menu;
    }

    /**
     * @param $i
     * @return Component
     */
    private function getComponents($i): Component
    {
        $component = new Component();
        $component->setComponentName('List Users' . $i);
        $component->setActive(true);
        return $component;
    }
}

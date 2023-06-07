<?php

declare(strict_types=1);

namespace App\User\Domain\Entity\Traits;

use App\Article\Domain\Entity\Post;
use App\Event\Domain\Entity\Event;
use App\Log\Domain\Entity\LogLogin;
use App\Log\Domain\Entity\LogLoginFailure;
use App\Log\Domain\Entity\LogRequest;
use App\Setting\Domain\Entity\Component;
use App\Setting\Domain\Entity\Menu;
use App\Setting\Domain\Entity\Setting;
use App\User\Domain\Entity\User;
use App\User\Domain\Entity\UserGroup;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class UserRelations
 *
 * @package App\User
 */
trait UserRelations
{
    /**
     * @var Collection<int, UserGroup>|ArrayCollection<int, UserGroup>
     */
    #[ORM\ManyToMany(
        targetEntity: UserGroup::class,
        inversedBy: 'users',
    )]
    #[ORM\JoinTable(name: 'user_has_user_group')]
    #[Groups([
        'User.userGroups',
    ])]
    protected Collection | ArrayCollection $userGroups;

    /**
     * @var Collection<int, LogRequest>|ArrayCollection<int, LogRequest>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: LogRequest::class,
    )]
    #[Groups([
        'User.logsRequest',
    ])]
    protected Collection | ArrayCollection $logsRequest;

    /**
     * @var Collection<int, LogLogin>|ArrayCollection<int, LogLogin>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: LogLogin::class,
    )]
    #[Groups([
        'User.logsLogin',
    ])]
    protected Collection | ArrayCollection $logsLogin;

    /**
     * @var Collection<int, LogLoginFailure>|ArrayCollection<int, LogLoginFailure>
     */
    #[ORM\OneToMany(
        mappedBy: 'user',
        targetEntity: LogLoginFailure::class,
    )]
    #[Groups([
        'User.logsLoginFailure',
    ])]
    protected Collection | ArrayCollection $logsLoginFailure;


    #[OneToOne(mappedBy: 'user', targetEntity: Setting::class)]
    #[Groups([
        'User.setting',
        User::SET_USER_PROFILE,
    ])]
    protected Setting|null $setting;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    #[Groups([
        'User.posts',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Event::class, orphanRemoval: true)]
    #[Groups([
        'User.events',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $events;

    #[ORM\ManyToMany(targetEntity: Menu::class, cascade: ["persist"])]
    #[ORM\JoinTable(name: 'user_menu')]
    #[Groups([
        'User.menus',
        User::SET_USER_PROFILE,
    ])]
    protected Collection $menus;

    #[ORM\ManyToMany(targetEntity: Component::class, cascade: ["persist"])]
    #[ORM\JoinTable(name: 'user_component')]
    #[Groups([
        'User.components',
        User::SET_USER_PROFILE,
    ])]
    protected Collection $components;

    /**
     * Getter for roles.
     *
     * Note that this will only return _direct_ roles that user has and
     * not the inherited ones!
     *
     * If you want to get user inherited roles you need to implement that
     * logic by yourself OR use eg. `/user/{uuid}/roles` API endpoint.
     *
     * @return array<int, string>
     */
    #[Groups([
        'User.roles',

        User::SET_USER_PROFILE,
    ])]
    public function getRoles(): array
    {
        return $this->userGroups
            ->map(static fn (UserGroup $userGroup): string => $userGroup->getRole()->getId())
            ->toArray();
    }

    /**
     * Getter for user groups collection.
     *
     * @return Collection<int, UserGroup>|ArrayCollection<int, UserGroup>
     */
    public function getUserGroups(): Collection | ArrayCollection
    {
        return $this->userGroups;
    }

    /**
     * Getter for user request log collection.
     *
     * @return Collection<int, LogRequest>|ArrayCollection<int, LogRequest>
     */
    public function getLogsRequest(): Collection | ArrayCollection
    {
        return $this->logsRequest;
    }

    /**
     * Getter for user login log collection.
     *
     * @return Collection<int, LogLogin>|ArrayCollection<int, LogLogin>
     */
    public function getLogsLogin(): Collection | ArrayCollection
    {
        return $this->logsLogin;
    }

    /**
     * Getter for user login failure log collection.
     *
     * @return Collection<int, LogLoginFailure>|ArrayCollection<int, LogLoginFailure>
     */
    public function getLogsLoginFailure(): Collection | ArrayCollection
    {
        return $this->logsLoginFailure;
    }

    /**
     * Method to attach new user group to user.
     */
    public function addUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->contains($userGroup) === false) {
            $this->userGroups->add($userGroup);
            $userGroup->addUser($this);
        }

        return $this;
    }

    /**
     * Method to remove specified user group from user.
     */
    public function removeUserGroup(UserGroup $userGroup): self
    {
        if ($this->userGroups->removeElement($userGroup)) {
            $userGroup->removeUser($this);
        }

        return $this;
    }

    /**
     * Method to remove all many-to-many user group relations from current user.
     */
    public function clearUserGroups(): self
    {
        $this->userGroups->clear();

        return $this;
    }

    /**
     * @return Setting|null
     */
    public function getSetting(): ?Setting
    {
        return $this->setting;
    }

    /**
     * @param Setting|null $setting
     */
    public function setSetting(?Setting $setting): void
    {
        $this->setting = $setting;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getPosts(): ArrayCollection|Collection
    {
        return $this->posts;
    }

    /**
     * @param ArrayCollection|Collection $posts
     */
    public function setPosts(ArrayCollection|Collection $posts): void
    {
        $this->posts = $posts;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getMenus(): ArrayCollection|Collection
    {
        return $this->menus;
    }

    /**
     * @param ArrayCollection|Collection $menus
     */
    public function setMenus(ArrayCollection|Collection $menus): void
    {
        $this->menus = $menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
        }
        return $this;
    }
    public function removeMenu(Menu $menu): self
    {
        $this->menus->removeElement($menu);
        return $this;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
        }
        return $this;
    }
    public function removeComponent(Component $component): self
    {
        $this->components->removeElement($component);
        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getComponents(): ArrayCollection|Collection
    {
        return $this->components;
    }

    /**
     * @param ArrayCollection|Collection $components
     */
    public function setComponents(ArrayCollection|Collection $components): void
    {
        $this->components = $components;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getEvents(): ArrayCollection|Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
        }
        return $this;
    }
    public function removeEvent(Event $event): self
    {
        $this->events->removeElement($event);
        return $this;
    }

    /**
     * @param ArrayCollection|Collection $events
     */
    public function setEvents(ArrayCollection|Collection $events): void
    {
        $this->events = $events;
    }
}

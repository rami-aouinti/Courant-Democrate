<?php

declare(strict_types=1);

namespace App\User\Domain\Entity\Traits;

use App\Article\Domain\Entity\Post;
use App\Chat\Domain\Entity\Message;
use App\Chat\Domain\Entity\Participant;
use App\Event\Domain\Entity\Event;
use App\Log\Domain\Entity\LogLogin;
use App\Log\Domain\Entity\LogLoginFailure;
use App\Log\Domain\Entity\LogRequest;
use App\Notification\Domain\Entity\Notification;
use App\Quiz\Domain\Entity\Category;
use App\Quiz\Domain\Entity\Group;
use App\Quiz\Domain\Entity\Language;
use App\Quiz\Domain\Entity\Question;
use App\Quiz\Domain\Entity\Quiz;
use App\Quiz\Domain\Entity\Score;
use App\Quiz\Domain\Entity\Workout;
use App\Resume\Domain\Entity\Education;
use App\Resume\Domain\Entity\Hobby;
use App\Resume\Domain\Entity\Profile;
use App\Resume\Domain\Entity\Project;
use App\Resume\Domain\Entity\Skill;
use App\Resume\Domain\Entity\Tool;
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

    #[OneToOne(mappedBy: 'user', targetEntity: Score::class)]
    #[Groups([
        'User.score',
        User::SET_USER_PROFILE,
    ])]
    protected Score|null $score;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    #[Groups([
        'User.posts',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $posts;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Notification::class, orphanRemoval: true)]
    #[Groups([
        'User.notifications',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $notifications;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Education::class, orphanRemoval: true)]
    #[Groups([
        'User.educations',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $educations;


    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Skill::class, orphanRemoval: true)]
    #[Groups([
        'User.skills',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $skills;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: \App\Resume\Domain\Entity\Language::class, orphanRemoval: true)]
    #[Groups([
        'User.languages',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $languages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Hobby::class, orphanRemoval: true)]
    #[Groups([
        'User.hobbies',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $hobbies;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Tool::class, orphanRemoval: true)]
    #[Groups([
        'User.tools',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $tools;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Project::class, orphanRemoval: true)]
    #[Groups([
        'User.projects',
        User::SET_USER_PROFILE,
    ])]
    protected Collection | ArrayCollection $projects;

    #[OneToOne(mappedBy: 'user', targetEntity: Profile::class)]
    #[Groups([
        'User.profile',
        User::SET_USER_PROFILE,
    ])]
    protected Profile|null $profile;

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


    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'users')]
    #[ORM\JoinTable(name: 'tbl_user_group')]
    private ?Collection $groups;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Question::class)]
    private Collection $questions;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Category::class)]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'student', targetEntity: Workout::class, orphanRemoval: true)]
    private Collection $workouts;

    #[ORM\OneToMany(mappedBy: 'created_by', targetEntity: Quiz::class, orphanRemoval: true)]
    private Collection $quizzes;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Language $prefered_language = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Participant::class, orphanRemoval: true)]
    private Collection $participants;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Message::class, orphanRemoval: true)]
    private Collection $messages;

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

    /**
     * @return Collection<int, Workout>
     */
    public function getWorkouts(): Collection
    {
        return $this->workouts;
    }

    public function addWorkout(Workout $workout): self
    {
        if (!$this->workouts->contains($workout)) {
            $this->workouts->add($workout);
            $workout->setStudent($this);
        }

        return $this;
    }

    public function removeWorkout(Workout $workout): self
    {
        if ($this->workouts->removeElement($workout)) {
            // set the owning side to null (unless already changed)
            if ($workout->getStudent() === $this) {
                $workout->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuizzes(): Collection
    {
        return $this->quizzes;
    }

    public function addQuiz(Quiz $quiz): self
    {
        if (!$this->quizzes->contains($quiz)) {
            $this->quizzes->add($quiz);
            $quiz->setCreatedBy($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): self
    {
        if ($this->quizzes->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getCreatedBy() === $this) {
                $quiz->setCreatedBy(null);
            }
        }

        return $this;
    }

    public function getPreferedLanguage(): ?Language
    {
        return $this->prefered_language;
    }

    public function setPreferedLanguage(?Language $prefered_language): self
    {
        $this->prefered_language = $prefered_language;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function addGroup(?Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups->add($group);
            $group->addUser($this);
        }

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        if ($this->groups->removeElement($group)) {
            $group->removeUser($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setCreatedBy($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getCreatedBy() === $this) {
                $question->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getCreatedBy() === $this) {
                $category->setCreatedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Score|null
     */
    public function getScore(): ?Score
    {
        return $this->score;
    }

    /**
     * @param Score|null $score
     */
    public function setScore(?Score $score): void
    {
        $this->score = $score;
    }

    /**
     * @return Collection|Participant[]
     */
    public function getParticipants(): Collection|array
    {
        return $this->participants;
    }

    public function addParticipant(Participant $participant): self
    {
        if (!$this->participants->contains($participant)) {
            $this->participants[] = $participant;
            $participant->setUser($this);
        }

        return $this;
    }

    public function removeParticipant(Participant $participant): self
    {
        if ($this->participants->removeElement($participant)) {
            // set the owning side to null (unless already changed)
            if ($participant->getUser() === $this) {
                $participant->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notifications): self
    {
        if (!$this->notifications->contains($notifications)) {
            $this->notifications[] = $notifications;
            $notifications->setUser($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notifications): self
    {
        if ($this->notifications->removeElement($notifications)) {
            // set the owning side to null (unless already changed)
            if ($notifications->getUser() === $this) {
                $notifications->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getEducations(): ArrayCollection|Collection
    {
        return $this->educations;
    }

    /**
     * @param ArrayCollection|Collection $educations
     */
    public function setEducations(ArrayCollection|Collection $educations): void
    {
        $this->educations = $educations;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getSkills(): ArrayCollection|Collection
    {
        return $this->skills;
    }

    /**
     * @param ArrayCollection|Collection $skills
     */
    public function setSkills(ArrayCollection|Collection $skills): void
    {
        $this->skills = $skills;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getLanguages(): ArrayCollection|Collection
    {
        return $this->languages;
    }

    /**
     * @param ArrayCollection|Collection $languages
     */
    public function setLanguages(ArrayCollection|Collection $languages): void
    {
        $this->languages = $languages;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getHobbies(): ArrayCollection|Collection
    {
        return $this->hobbies;
    }

    /**
     * @param ArrayCollection|Collection $hobbies
     */
    public function setHobbies(ArrayCollection|Collection $hobbies): void
    {
        $this->hobbies = $hobbies;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getTools(): ArrayCollection|Collection
    {
        return $this->tools;
    }

    /**
     * @param ArrayCollection|Collection $tools
     */
    public function setTools(ArrayCollection|Collection $tools): void
    {
        $this->tools = $tools;
    }

    /**
     * @return Profile|null
     */
    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile|null $profile
     */
    public function setProfile(?Profile $profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return ArrayCollection|Collection
     */
    public function getProjects(): ArrayCollection|Collection
    {
        return $this->projects;
    }

    /**
     * @param ArrayCollection|Collection $projects
     */
    public function setProjects(ArrayCollection|Collection $projects): void
    {
        $this->projects = $projects;
    }
}

<?php

class Character
{
    public $name;
    public $lifePoints;
    public $defense;
    public $attack;
    public $weapon;
    public $shield;

    public function __construct($name, $lifePoints, $defense, $attack)
    {
        $this->name = $name;
        $this->lifePoints = $lifePoints;
        $this->defense = $defense;
        $this->attack = $attack;
    }

    public function attack(Ennemi $ennemi)
    {
        if ($this->weapon instanceof Weapon) {
            $this->weapon->durabilite -= 1;
            $ennemi->defense(max(0, $this->attack + ($this->weapon->durabilite > 0 ? $this->weapon->damage : 0)));
            if ($this->weapon->durabilite ==  0) {
                echo "Votre arme est cassée";
                $this->attack -= $this->weapon->damage;
                $this->weapon = null;
            }
        } else {
            $ennemi->defense($this->attack);
        }
    }

    public function defense($damage)
    {
        if ($this->shield instanceof Shield) {
            $this->shield->durabilite -= 1;
            $this->lifePoints -= max(0, $damage - $this->defense - ($this->shield->durabilite > 0 ? $this->shield->defense : 0));
            if ($this->shield->durabilite ==  0) {
                echo "Votre bouclier est cassé";
                $this->defense -= $this->shield->defense;
                $this->shield = null;
            }
        } else {
            $this->lifePoints -= ($damage - $this->defense);
        }
    }

    public function mount(Mount $monture)
    {
        $this->attack += $monture->bonusDamage;
        $this->defense += $monture->bonusDefense;
    }

    public function getWeapon(Weapon $weapon)
    {
        $this->attack += $weapon->damage;
        $this->weapon = $weapon;
    }

    public function getShield(Shield $shield)
    {
        $this->defense += $shield->defense;
        $this->shield = $shield;
    }

    public function display()
    {
        echo "Pseudo : " . $this->name . "\n";
        echo "Point de vie : " . $this->lifePoints . "\n";
        echo "Défense : " . $this->defense . "\n";
        echo "Attaque : " . $this->attack . "\n";
    }
}

class Weapon
{
    public $damage;
    public $durabilite;

    public function __construct($damage, $durabilite)
    {
        $this->damage = $damage;
        $this->durabilite = $durabilite;
    }
}

class Shield
{
    public $defense;
    public $durabilite;

    public function __construct($defense, $durabilite)
    {
        $this->defense = $defense;
        $this->durabilite = $durabilite;
    }
}

class Mount
{
    public $bonusDamage;
    public $bonusDefense;

    public function __construct($bonusDamage, $bonusDefense)
    {
        $this->bonusDamage = $bonusDamage;
        $this->bonusDefense = $bonusDefense;
    }
}

class Ennemi extends Character
{
    public $boss;
    public $weapon;
    public $shield;

    public function __construct($name, $lifePoints, $defense, $attack, $boss = false)
    {
        parent::__construct($name, $lifePoints, $defense, $attack);
        $this->boss = $boss;
    }

    public function attack(Character $character)
    {
        if ($this->weapon instanceof Weapon) {
            $this->weapon->durabilite -= 1;
            $character->defense(max(0, $this->attack + ($this->weapon->durabilite > 0 ? $this->weapon->damage : 0)));
            if ($this->weapon->durabilite ==  0) {
                echo "Votre arme est cassée";
                $this->attack -= $this->weapon->damage;
                $this->weapon = null;
            }
        } else {
            $character->defense($this->attack);
        }
    }

    public function defense($damage)
    {
        if ($this->shield instanceof Shield) {
            $this->shield->durabilite -= 1;
            $this->lifePoints -= max(0, $damage - $this->defense - ($this->shield->durabilite > 0 ? $this->shield->defense : 0));
            if ($this->shield->durabilite ==  0) {
                echo "Votre bouclier est cassé";
                $this->defense -= $this->shield->defense;
                $this->shield = null;
            }
        } else {
            $this->lifePoints -= ($damage - $this->defense);
        }
    }

    public function mount(Mount $monture)
    {
        $this->attack += $monture->bonusDamage;
        $this->defense += $monture->bonusDefense;
    }

    public function getWeapon(Weapon $weapon)
    {
        $this->attack += $weapon->damage;
        $this->weapon = $weapon;
    }

    public function getShield(Shield $shield)
    {
        $this->defense += $shield->defense;
        $this->shield = $shield;
    }

    public function display()
    {
        echo "Pseudo : " . $this->name . "\n";
        echo "Point de vie : " . $this->lifePoints . "\n";
        echo "Défense : " . $this->defense . "\n";
        echo "Attaque : " . $this->attack . "\n";
    }
}

$ennemies = [
    new Ennemi("Ennemi 1", 100, 0, 1),
    new Ennemi("Ennemi 2", 100, 0, 1),
    new Ennemi("Ennemi 3", 50, 0, 1),
];

$hero = new Character("John Doe", 100, 0, 20);

$hero->getWeapon(new Weapon(10, 2));
$hero->getShield(new Shield(100, 5));

while (count($ennemies) > 0 && $hero->lifePoints > 0) {
    $ennemi = array_shift($ennemies);
    echo "Vous affrontez " . $ennemi->name . "\n";

    // Fight loop 
    while ($ennemi->lifePoints > 0 && $hero->lifePoints > 0) {
        $hero->attack($ennemi);
        echo $ennemi->name . " a maintenant " . $ennemi->lifePoints . " points de vie\n";
        if ($ennemi->lifePoints <= 0) {
            echo "Vous avez vaincu " . $ennemi->name . "\n";
            break;
        }
        $ennemi->attack($hero);
        echo "Vous avez maintenant " . $hero->lifePoints . " points de vie\n";
    }
}

if ($hero->lifePoints <= 0) {
    echo "Vous avez été vaincu\n";
} else {
    echo "Vous avez vaincu tous les ennemis\n";
}

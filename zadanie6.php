<?php

$arr = [
    ['id' => 1, 'parent_id' => 0, 'name' => 'Elektronika'],
    ['id' => 2, 'parent_id' => 6, 'name' => 'test'],
    ['id' => 3, 'parent_id' => 1, 'name' => 'Roboty'],
    ['id' => 4, 'parent_id' => 6, 'name' => 'Piłka nożna'],
    ['id' => 5, 'parent_id' => 0, 'name' => 'Turystyka'],
    ['id' => 6, 'parent_id' => 0, 'name' => 'Sport'],
    ['id' => 7, 'parent_id' => 1, 'name' => 'Telefony'],
    ['id' => 8, 'parent_id' => 1, 'name' => 'Laptopy'],
    ['id' => 9, 'parent_id' => 1, 'name' => 'Tablety'],
    ['id' => 10, 'parent_id' => 6, 'name' => 'Siłownia i fitness'],
];

$treeManager = new TreeManager($arr);
$treeManager->generateTree();

echo '<pre>';
print_r($treeManager->getRoot());
echo '</pre>';


class Category
{
    private int $id;
    private int $parentId;
    private string $name;
    private array $children = [];

    public function __construct(int $id, int $parentId, string $name)
    {
        $this->id = $id;
        $this->parentId = $parentId;
        $this->name = $name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getParentId(): int
    {
        return $this->parentId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addChild(Category $category)
    {
        $this->children[] = $category;
    }

    public function getChildren(): array
    {
        return $this->children;
    }
}

class TreeManager
{
    private array $categories;
    private Category $root;

    public function __construct(array $categories)
    {
        $this->categories = $categories;
        $this->root = new Category(0, 0, 'Root');
    }

    public function getRoot(): Category
    {
        return $this->root;
    }

    public function generateTree(?Category $cat = null)
    {
        $cat = $cat ?? $this->root;

        foreach ($this->categories as ['id' => $id, 'parent_id' => $parentId, 'name' => $name]) {
            if ($parentId === $cat->getId()) {
                $newCat = new Category($id, $parentId, $name);
                $cat->addChild($newCat);
                $this->generateTree($newCat);
            }
        }
    }
}
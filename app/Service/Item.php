<?php
declare(strict_types=1);

namespace App\Service;

use App\Models\Item as ItemModel;

use function App\site_local_url;

final class Item
{
    private ItemModel $itemModel;

    public function __construct(?ItemModel $itemModel = null)
    {
        $this->itemModel = $itemModel ?? new ItemModel();
    }

    public function create(int|string $userId, array $itemDetails): string|bool
    {
        return $this->itemModel->insert($userId, $itemDetails);
    }

    public function update(int|string $userId, array $itemDetails): bool
    {
        return $this->itemModel->update($userId, $itemDetails);
    }

    public function getFromIdName(string $idName): array|false
    {
        return $this->itemModel->get($idName);
    }

    public function getFromUserId(int|string $userId): array|false
    {
        return $this->itemModel->get((string) $userId);
    }

    public function hasUserAnItem(int|string $userId): bool
    {
        return $this->itemModel->has_user_an_item($userId);
    }

    public function doesItemIdNameExist(string $idName): bool
    {
        return $this->itemModel->does_id_name_exist($idName);
    }

    public function getUserItemUrl(string $itemIdName): string
    {
        return site_local_url('/p/' . $itemIdName);
    }
}


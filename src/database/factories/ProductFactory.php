<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $brands = [
            'Sokolov', 'Cartier', 'Pandora', 'Tous', 'Chopard',
            'Van Cleef & Arpels', 'Tiffany & Co.', 'Bvlgari', 'Messika'
        ];

        $categories = [
            'Кольцо', 'Серьги', 'Браслет', 'Подвеска', 'Цепочка',
            'Колье', 'Брошь', 'Чокер', 'Пирсинг'
        ];

        $metals = [
            'Золото', 'Серебро', 'Белое золото', 'Розовое золото', 'Платина'
        ];

        $stones = [
            'без камней', 'с фианитами', 'с бриллиантами', 'с рубинами',
            'с топазами', 'с сапфирами', 'с изумрудами', 'с жемчугом'
        ];

        $brand = $this->faker->randomElement($brands);
        $category = $this->faker->randomElement($categories);
        $metal = $this->faker->randomElement($metals);
        $stone = $this->faker->randomElement($stones);

        $title = sprintf(
            '%s %s (%s, %s)',
            $brand,
            $category,
            $metal,
            $stone
        );

        $description = $this->faker->realTextBetween(80, 150);

        $price = $this->faker->numberBetween(500, 100000);

        $discount = $this->faker->numberBetween(0, 30);

        $imagesArray = [];
        for ($i = 0; $i < $this->faker->numberBetween(1, 4); $i++) {
            $imagesArray[] = $this->faker->imageUrl(600, 600, 'jewelry', true);
        }

        $imagesJson = json_encode($imagesArray);

        return [
            'title'       => $title,
            'brand'       => $brand,
            'category'    => $category,
            'description' => $description,
            'price'       => $price,
            'discount'    => $discount,
            'images'      => $imagesJson,
        ];
    }
}

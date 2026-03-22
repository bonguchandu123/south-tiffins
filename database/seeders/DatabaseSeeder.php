<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->insertOrIgnore([
            'name'       => 'South Tiffins Admin',
            'email'      => 'admin@southtiffins.com',
            'password'   => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('counter_access')->insertOrIgnore([
            'pin'        => '1234',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('categories')->insert([
            [
                'name_en'    => 'Breakfast',
                'name_te'    => 'అల్పాహారం',
                'sort_order' => 1,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en'    => 'Tiffin',
                'name_te'    => 'టిఫిన్',
                'sort_order' => 2,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en'    => 'Rice Items',
                'name_te'    => 'అన్నం వంటకాలు',
                'sort_order' => 3,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name_en'    => 'Drinks',
                'name_te'    => 'పానీయాలు',
                'sort_order' => 4,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        $breakfast = DB::table('categories')->where('name_en', 'Breakfast')->first()->id;
        $tiffin    = DB::table('categories')->where('name_en', 'Tiffin')->first()->id;
        $rice      = DB::table('categories')->where('name_en', 'Rice Items')->first()->id;
        $drinks    = DB::table('categories')->where('name_en', 'Drinks')->first()->id;

        DB::table('menu_items')->insert([

            [
                'category_id'    => $breakfast,
                'name_en'        => 'Idli',
                'name_te'        => 'ఇడ్లీ',
                'description_en' => 'Soft steamed rice cakes served with coconut chutney and sambar',
                'description_te' => 'కొబ్బరి చట్నీ మరియు సాంబారుతో వడ్డించిన మెత్తని ఆవిరి అన్న కేకులు',
                'price'          => 40.00,
                'image_url'      => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 1,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $breakfast,
                'name_en'        => 'Vada',
                'name_te'        => 'వడ',
                'description_en' => 'Crispy fried lentil donuts served with chutney and sambar',
                'description_te' => 'చట్నీ మరియు సాంబారుతో వడ్డించిన క్రిస్పీ పప్పు వడలు',
                'price'          => 30.00,
                'image_url'      => 'https://images.unsplash.com/photo-1630383249896-424e482df921?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 2,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $breakfast,
                'name_en'        => 'Idli Vada Combo',
                'name_te'        => 'ఇడ్లీ వడ కాంబో',
                'description_en' => '2 Idli and 1 Vada served with chutney and sambar',
                'description_te' => '2 ఇడ్లీ మరియు 1 వడ చట్నీ మరియు సాంబారుతో',
                'price'          => 60.00,
                'image_url'      => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 3,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $breakfast,
                'name_en'        => 'Pongal',
                'name_te'        => 'పొంగల్',
                'description_en' => 'Creamy rice and lentil porridge tempered with ghee and spices',
                'description_te' => 'నెయ్యి మరియు మసాలాలతో వేయించిన క్రీమీ అన్నం మరియు పప్పు గంజి',
                'price'          => 50.00,
                'image_url'      => 'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 4,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $breakfast,
                'name_en'        => 'Upma',
                'name_te'        => 'ఉప్మా',
                'description_en' => 'Savory semolina porridge cooked with vegetables and spices',
                'description_te' => 'కూరగాయలు మరియు మసాలాలతో వండిన రవ్వ ఉప్మా',
                'price'          => 40.00,
                'image_url'      => 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 5,
                'created_at'     => now(),
                'updated_at'     => now()
            ],

            [
                'category_id'    => $tiffin,
                'name_en'        => 'Plain Dosa',
                'name_te'        => 'సాదా దోశ',
                'description_en' => 'Thin crispy crepe made from fermented rice and lentil batter',
                'description_te' => 'పులిసిన అన్నం మరియు పప్పు పిండితో తయారు చేసిన సన్నని క్రిస్పీ దోశ',
                'price'          => 50.00,
                'image_url'      => 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 1,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $tiffin,
                'name_en'        => 'Masala Dosa',
                'name_te'        => 'మసాలా దోశ',
                'description_en' => 'Crispy dosa filled with spiced potato masala served with chutney and sambar',
                'description_te' => 'చట్నీ మరియు సాంబారుతో వడ్డించిన మసాలా బంగాళాదుంప నింపిన క్రిస్పీ దోశ',
                'price'          => 70.00,
                'image_url'      => 'https://images.unsplash.com/photo-1589301760014-d929f3979dbc?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 2,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $tiffin,
                'name_en'        => 'Ghee Roast Dosa',
                'name_te'        => 'నెయ్యి రోస్ట్ దోశ',
                'description_en' => 'Extra crispy dosa roasted in pure ghee with a golden finish',
                'description_te' => 'స్వచ్ఛమైన నెయ్యిలో బంగారు రంగుతో వేయించిన అదనపు క్రిస్పీ దోశ',
                'price'          => 80.00,
                'image_url'      => 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 3,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $tiffin,
                'name_en'        => 'Uttapam',
                'name_te'        => 'ఉత్తప్పం',
                'description_en' => 'Thick soft pancake topped with onions, tomatoes and green chillies',
                'description_te' => 'ఉల్లిపాయలు, టమాటాలు మరియు పచ్చి మిర్చితో నింపిన మందమైన మెత్తని పాన్కేక్',
                'price'          => 60.00,
                'image_url'      => 'https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 4,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $tiffin,
                'name_en'        => 'Pesarattu',
                'name_te'        => 'పెసరట్టు',
                'description_en' => 'Green moong dal crepe served with ginger chutney',
                'description_te' => 'అల్లం చట్నీతో వడ్డించిన పచ్చి పెసర పప్పు పెసరట్టు',
                'price'          => 55.00,
                'image_url'      => 'https://images.unsplash.com/photo-1668236543090-82eba5ee5976?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 5,
                'created_at'     => now(),
                'updated_at'     => now()
            ],

            [
                'category_id'    => $rice,
                'name_en'        => 'Plain Rice',
                'name_te'        => 'సాదా అన్నం',
                'description_en' => 'Steamed white rice served with dal and pickle',
                'description_te' => 'పప్పు మరియు ఊరగాయతో వడ్డించిన ఆవిరి తెల్ల అన్నం',
                'price'          => 60.00,
                'image_url'      => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 1,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $rice,
                'name_en'        => 'Curd Rice',
                'name_te'        => 'పెరుగు అన్నం',
                'description_en' => 'Creamy yogurt rice tempered with mustard seeds and curry leaves',
                'description_te' => 'ఆవాలు మరియు కరివేపాకుతో వేయించిన క్రీమీ పెరుగు అన్నం',
                'price'          => 60.00,
                'image_url'      => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 2,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $rice,
                'name_en'        => 'Lemon Rice',
                'name_te'        => 'నిమ్మ అన్నం',
                'description_en' => 'Tangy rice made with fresh lemon juice, turmeric and peanuts',
                'description_te' => 'తాజా నిమ్మ రసం, పసుపు మరియు వేరుశెనగలతో తయారు చేసిన పులుపు అన్నం',
                'price'          => 65.00,
                'image_url'      => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 3,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $rice,
                'name_en'        => 'Tomato Rice',
                'name_te'        => 'టమాటా అన్నం',
                'description_en' => 'Spicy tangy rice cooked with fresh tomatoes and Indian spices',
                'description_te' => 'తాజా టమాటాలు మరియు భారతీయ మసాలాలతో వండిన మసాలా అన్నం',
                'price'          => 65.00,
                'image_url'      => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 4,
                'created_at'     => now(),
                'updated_at'     => now()
            ],

            [
                'category_id'    => $drinks,
                'name_en'        => 'Filter Coffee',
                'name_te'        => 'ఫిల్టర్ కాఫీ',
                'description_en' => 'Traditional South Indian filter coffee with frothy milk',
                'description_te' => 'పొంగిన పాలతో సంప్రదాయ దక్షిణ భారత ఫిల్టర్ కాఫీ',
                'price'          => 20.00,
                'image_url'      => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 1,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $drinks,
                'name_en'        => 'Masala Chai',
                'name_te'        => 'మసాలా చాయ్',
                'description_en' => 'Spiced Indian tea brewed with ginger, cardamom and milk',
                'description_te' => 'అల్లం, యాలకులు మరియు పాలతో కాచిన మసాలా టీ',
                'price'          => 15.00,
                'image_url'      => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 2,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $drinks,
                'name_en'        => 'Buttermilk',
                'name_te'        => 'మజ్జిగ',
                'description_en' => 'Chilled salted buttermilk with cumin and fresh coriander',
                'description_te' => 'జీలకర్ర మరియు తాజా కొత్తిమీరతో చల్లని మజ్జిగ',
                'price'          => 20.00,
                'image_url'      => 'https://images.unsplash.com/photo-1563729784474-d77dbb933a9e?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 3,
                'created_at'     => now(),
                'updated_at'     => now()
            ],
            [
                'category_id'    => $drinks,
                'name_en'        => 'Fresh Lime Soda',
                'name_te'        => 'నిమ్మ సోడా',
                'description_en' => 'Refreshing lime soda with a hint of salt and sugar',
                'description_te' => 'ఉప్పు మరియు చక్కెర సూచనతో రిఫ్రెషింగ్ నిమ్మ సోడా',
                'price'          => 30.00,
                'image_url'      => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=400&q=80',
                'is_veg'         => 1,
                'is_available'   => 1,
                'sort_order'     => 4,
                'created_at'     => now(),
                'updated_at'     => now()
            ]
        ]);

        DB::table('parlour_tables')->insert([
            ['table_number' => '1', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '2', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '3', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '4', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['table_number' => '5', 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
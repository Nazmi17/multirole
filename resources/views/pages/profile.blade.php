@extends('layouts-pages.app')

@section('content')
    @php
        $teamMembers = [
            // [
            //     'name' => 'Chef Lina',
            //     'role' => 'Head Chef',
            //     'specialty' => 'Traditional Sundanese Cuisine',
            //     'bio' => 'Passionate about preserving authentic flavors with a modern presentation.',
            //     'photo' => 'https://static.wixstatic.com/media/dea205_2f3a712b224041ae92aa78009637e4ae~mv2.png?originWidth=384&originHeight=384',
            // ],
            // [
            //     'name' => 'Chef Dimas',
            //     'role' => 'Sous Chef',
            //     'specialty' => 'Seasonal Menus',
            //     'bio' => 'Dedicated to fresh ingredients and vibrant seasonal dishes.',
            //     'photo' => 'https://static.wixstatic.com/media/dea205_2f3a712b224041ae92aa78009637e4ae~mv2.png?originWidth=384&originHeight=384',
            // ],
            // [
            //     'name' => 'Alya Rahman',
            //     'role' => 'Restaurant Manager',
            //     'specialty' => 'Guest Experience',
            //     'bio' => 'Ensures every guest feels welcomed and celebrated.',
            //     'photo' => 'https://static.wixstatic.com/media/dea205_2f3a712b224041ae92aa78009637e4ae~mv2.png?originWidth=384&originHeight=384',
            // ],
            // [
            //     'name' => 'Rizky Hanif',
            //     'role' => 'Culinary Curator',
            //     'specialty' => 'Menu Storytelling',
            //     'bio' => 'Brings cultural context and storytelling to every dish served.',
            //     'photo' => 'https://static.wixstatic.com/media/dea205_2f3a712b224041ae92aa78009637e4ae~mv2.png?originWidth=384&originHeight=384',
            // ],
        ];
    @endphp

    <div class="min-h-screen bg-background">
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    About Nazmi Restaurant
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Discover our story, mission, and the passionate team behind authentic Sundanese cuisine.
                </p>
            </div>
        </section>

        <section class="w-full py-16 md:py-24">
            <div class="max-w-[100rem] mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div>
                        <div class="relative rounded-3xl overflow-hidden shadow-xl">
                            <img
                                src="https://static.wixstatic.com/media/dea205_7b1a83e8376a40328fad7b0e8c2d4434~mv2.png?originWidth=640&originHeight=448"
                                alt="Nazmi Restaurant interior"
                                class="w-full h-auto"
                            />
                        </div>
                    </div>

                    <div>
                        <h2 class="font-heading text-4xl md:text-5xl text-foreground mb-6">
                            Our Story
                        </h2>
                        <p class="font-paragraph text-lg text-foreground/80 mb-6 leading-relaxed">
                            Nazmi Restaurant was founded with a deep passion for preserving and celebrating the rich culinary traditions of Sundanese cuisine.
                            Our journey began with a simple mission: to bring authentic flavors and warm Indonesian hospitality to every guest who walks through our doors.
                        </p>
                        <p class="font-paragraph text-lg text-foreground/80 mb-6 leading-relaxed">
                            Every dish we serve tells a story of tradition, passed down through generations. We use only the freshest ingredients,
                            traditional cooking methods, and recipes that have been perfected over time to ensure an authentic dining experience.
                        </p>
                        <p class="font-paragraph text-lg text-foreground/80 leading-relaxed">
                            This website serves as a digital showcase of our restaurant, created as part of a school project to demonstrate
                            modern web design principles while honoring our cultural heritage and culinary traditions.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full bg-primary py-16 md:py-24">
            <div class="max-w-[100rem] mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="font-heading text-4xl md:text-5xl text-foreground mb-6">
                        Our Mission & Values
                    </h2>
                    <p class="font-paragraph text-lg text-foreground/80 max-w-3xl mx-auto">
                        What drives us to serve authentic Sundanese cuisine every day.
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-xl p-8 shadow-md text-center">
                        <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <span class="font-heading text-4xl text-secondary-foreground">🍽️</span>
                        </div>
                        <h3 class="font-heading text-2xl text-foreground mb-4">Authenticity</h3>
                        <p class="font-paragraph text-foreground/70 leading-relaxed">
                            We stay true to traditional Sundanese recipes and cooking methods, ensuring every dish is authentic and flavorful.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-8 shadow-md text-center">
                        <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <span class="font-heading text-4xl text-secondary-foreground">❤️</span>
                        </div>
                        <h3 class="font-heading text-2xl text-foreground mb-4">Hospitality</h3>
                        <p class="font-paragraph text-foreground/70 leading-relaxed">
                            We welcome every guest with warmth and care, creating a dining experience that feels like home.
                        </p>
                    </div>

                    <div class="bg-white rounded-xl p-8 shadow-md text-center">
                        <div class="bg-secondary rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-6">
                            <span class="font-heading text-4xl text-secondary-foreground">🌿</span>
                        </div>
                        <h3 class="font-heading text-2xl text-foreground mb-4">Quality</h3>
                        <p class="font-paragraph text-foreground/70 leading-relaxed">
                            We source the freshest ingredients and maintain the highest standards in every aspect of our service.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full py-16 md:py-24 min-h-[400px]">
            <div class="max-w-[100rem] mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="font-heading text-4xl md:text-5xl text-foreground mb-6">
                        Meet Our Team
                    </h2>
                    <p class="font-paragraph text-lg text-foreground/80 max-w-3xl mx-auto">
                        The passionate people who bring Sundanese culinary traditions to life.
                    </p>
                </div>

                {{-- TODO: replace with Laravel backend logic to load team profiles. --}}
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($teamMembers as $member)
                        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl hover:scale-105 transition duration-300">
                            <div class="relative aspect-square overflow-hidden">
                                <img
                                    src="{{ $member['photo'] }}"
                                    alt="{{ $member['name'] }}"
                                    class="w-full h-full object-cover"
                                />
                            </div>
                            <div class="p-6">
                                <h3 class="font-heading text-xl text-foreground mb-2">
                                    {{ $member['name'] }}
                                </h3>
                                <p class="font-paragraph text-secondary font-bold text-sm mb-3">
                                    {{ $member['role'] }}
                                </p>
                                <p class="font-paragraph text-foreground/60 text-sm mb-3">
                                    Specialty: {{ $member['specialty'] }}
                                </p>
                                <p class="font-paragraph text-foreground/70 text-sm leading-relaxed line-clamp-3">
                                    {{ $member['bio'] }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="w-full bg-accent-muted-red/10 py-16">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <div class="bg-white rounded-2xl p-8 md:p-12 shadow-lg max-w-4xl mx-auto">
                    <span class="font-heading text-5xl mb-4 block">📚</span>
                    <h2 class="font-heading text-3xl md:text-4xl text-foreground mb-4">
                        School Project
                    </h2>
                    <p class="font-paragraph text-lg text-foreground/80 leading-relaxed">
                        This website was created as part of a school project to demonstrate modern web development principles,
                        responsive design, and user experience best practices. It showcases the features and identity of Nazmi Restaurant
                        while celebrating the rich heritage of Sundanese cuisine.
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection

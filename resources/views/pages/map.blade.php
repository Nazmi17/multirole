@extends('layouts-pages.app')

@section('content')
    <div class="min-h-screen bg-background">
        <section class="w-full bg-primary pt-32 pb-16 md:pt-40 md:pb-20">
            <div class="max-w-[100rem] mx-auto px-6 text-center">
                <h1 class="font-heading text-5xl md:text-6xl text-foreground mb-6">
                    Visit Us
                </h1>
                <p class="font-paragraph text-lg md:text-xl text-foreground/80 max-w-3xl mx-auto">
                    Find your way to Nazmi Restaurant and experience authentic Sundanese cuisine.
                </p>
            </div>
        </section>

        <section class="w-full py-16 md:py-24">
            <div class="max-w-[100rem] mx-auto px-6">
                <div class="grid lg:grid-cols-2 gap-12">
                    <div class="rounded-2xl overflow-hidden shadow-xl">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126748.56347862248!2d107.57311709999999!3d-6.903444399999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e6398252477f%3A0x146a1f93d3e815b2!2sBandung%2C%20Bandung%20City%2C%20West%20Java!5e0!3m2!1sen!2sid!4v1234567890123!5m2!1sen!2sid"
                            width="100%"
                            height="500"
                            style="border: 0;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Nazmi Restaurant Location"
                            class="w-full h-[500px]"
                        ></iframe>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <h2 class="font-heading text-3xl md:text-4xl text-foreground mb-6">
                                Contact Information
                            </h2>
                            <p class="font-paragraph text-lg text-foreground/80 leading-relaxed">
                                We're located in the heart of Bandung, West Java. Come visit us and experience the authentic taste of Sundanese cuisine.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="bg-secondary rounded-xl w-12 h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="w-6 h-6 text-secondary-foreground">📍</span>
                                    </div>
                                    <div>
                                        <h3 class="font-heading text-xl text-foreground mb-2">Address</h3>
                                        <p class="font-paragraph text-foreground/70 leading-relaxed">
                                            Jl. Sundanese Heritage No. 123<br />
                                            Bandung, West Java 40111<br />
                                            Indonesia
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="bg-secondary rounded-xl w-12 h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="w-6 h-6 text-secondary-foreground">📞</span>
                                    </div>
                                    <div>
                                        <h3 class="font-heading text-xl text-foreground mb-2">Phone</h3>
                                        <p class="font-paragraph text-foreground/70">
                                            +62 812-3456-7890
                                        </p>
                                        <p class="font-paragraph text-foreground/70">
                                            +62 821-9876-5432
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="bg-secondary rounded-xl w-12 h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="w-6 h-6 text-secondary-foreground">✉️</span>
                                    </div>
                                    <div>
                                        <h3 class="font-heading text-xl text-foreground mb-2">Email</h3>
                                        <p class="font-paragraph text-foreground/70">
                                            info@nazmirestaurant.com
                                        </p>
                                        <p class="font-paragraph text-foreground/70">
                                            reservation@nazmirestaurant.com
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-white rounded-xl p-6 shadow-md hover:shadow-lg transition duration-300">
                                <div class="flex items-start gap-4">
                                    <div class="bg-secondary rounded-xl w-12 h-12 flex items-center justify-center flex-shrink-0">
                                        <span class="w-6 h-6 text-secondary-foreground">⏰</span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-heading text-xl text-foreground mb-3">Opening Hours</h3>
                                        <div class="space-y-2 font-paragraph text-foreground/70">
                                            <div class="flex justify-between">
                                                <span>Monday - Friday</span>
                                                <span class="font-bold">10:00 - 22:00</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Saturday</span>
                                                <span class="font-bold">09:00 - 23:00</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span>Sunday</span>
                                                <span class="font-bold">09:00 - 22:00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full bg-primary py-16 md:py-24">
            <div class="max-w-[100rem] mx-auto px-6">
                <div class="text-center">
                    <h2 class="font-heading text-3xl md:text-4xl text-foreground mb-6">
                        How to Get Here
                    </h2>
                    <p class="font-paragraph text-lg text-foreground/80 max-w-3xl mx-auto mb-8">
                        We're easily accessible by various modes of transportation.
                    </p>
                    <div class="grid md:grid-cols-3 gap-8 mt-12">
                        <div class="bg-white rounded-xl p-8 shadow-md">
                            <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="font-heading text-3xl text-secondary-foreground">🚗</span>
                            </div>
                            <h3 class="font-heading text-xl text-foreground mb-3">By Car</h3>
                            <p class="font-paragraph text-foreground/70 leading-relaxed">
                                Free parking available for customers. Located 5 minutes from Bandung city center.
                            </p>
                        </div>
                        <div class="bg-white rounded-xl p-8 shadow-md">
                            <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="font-heading text-3xl text-secondary-foreground">🚌</span>
                            </div>
                            <h3 class="font-heading text-xl text-foreground mb-3">By Public Transport</h3>
                            <p class="font-paragraph text-foreground/70 leading-relaxed">
                                Bus stop nearby. Take route 12 or 15 and get off at Sundanese Heritage stop.
                            </p>
                        </div>
                        <div class="bg-white rounded-xl p-8 shadow-md">
                            <div class="bg-secondary rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <span class="font-heading text-3xl text-secondary-foreground">🏍️</span>
                            </div>
                            <h3 class="font-heading text-xl text-foreground mb-3">By Motorcycle</h3>
                            <p class="font-paragraph text-foreground/70 leading-relaxed">
                                Motorcycle parking available. Easy access from main road with clear signage.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

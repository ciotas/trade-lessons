<template>
    <app-layout>
        <hero></hero>
        <feature></feature>
        <case></case>
        <now></now>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Welcome from '@/Jetstream/Welcome'
    import Hero from '@/Pages/Home/Hero'
    import Feature from '@/Pages/Home/Feature'
    import Now from '@/Pages/Home/Now'
    import Case from '@/Pages/Home/Case'

    export default {
        components: {
            AppLayout,
            Welcome,
            Hero,
            Feature,
            Now,
            Case
        },
    }
</script>

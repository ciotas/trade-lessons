<template>
    <app-layout>
        <hero></hero>
        <problem></problem>
        <feature></feature>
        <case></case>
        <refuse></refuse>
        <now></now>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Hero from '@/Pages/Home/Hero'
    import Feature from '@/Pages/Home/Feature'
    import Problem from '@/Pages/Home/Problem'
    import Refuse from '@/Pages/Home/Refuse'
    import Now from '@/Pages/Home/Now'
    import Case from '@/Pages/Home/Case'

    export default {
        components: {
            AppLayout,
            Hero,
            Feature,
            Problem,
            Refuse,
            Now,
            Case
        },
    }
</script>

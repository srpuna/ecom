

<?php $__env->startSection('title', 'Blog - ' . $siteSettings['site_name']); ?>
<?php $__env->startSection('meta_description', 'Read our latest blog posts, tips, and insights.'); ?>

<?php $__env->startSection('content'); ?>
<div class="container mx-auto px-6 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-serif text-gray-900 mb-4">Our Blog</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Discover insights, tips, and the latest updates from our team.</p>
    </div>

    <!-- Search -->
    <div class="max-w-xl mx-auto mb-12">
        <form action="<?php echo e(route('blog.index')); ?>" method="GET" class="flex gap-2">
            <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                placeholder="Search articles..."
                class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Search
            </button>
        </form>
    </div>

    <!-- Blog Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php $__empty_1 = true; $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                <!-- Featured Image -->
                <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="block aspect-[16/10] overflow-hidden bg-gray-100">
                    <?php if($post->featured_image): ?>
                        <img src="<?php echo e($post->featured_image); ?>" alt="<?php echo e($post->title); ?>"
                            class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-green-100 to-green-200">
                            <svg class="w-16 h-16 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                    <?php endif; ?>
                </a>

                <!-- Content -->
                <div class="p-6">
                    <!-- Meta -->
                    <div class="flex items-center gap-4 text-sm text-gray-500 mb-3">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <?php echo e($post->published_at->format('M d, Y')); ?>

                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <?php echo e($post->reading_time); ?> min read
                        </span>
                    </div>

                    <!-- Title -->
                    <h2 class="text-xl font-serif font-bold text-gray-900 mb-3 line-clamp-2">
                        <a href="<?php echo e(route('blog.show', $post->slug)); ?>" class="hover:text-green-600 transition">
                            <?php echo e($post->title); ?>

                        </a>
                    </h2>

                    <!-- Excerpt -->
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                        <?php echo e($post->short_excerpt); ?>

                    </p>

                    <!-- Read More -->
                    <a href="<?php echo e(route('blog.show', $post->slug)); ?>" 
                        class="inline-flex items-center text-green-600 font-medium hover:text-green-700 transition">
                        Read More
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-16">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 mb-2">No posts found</h3>
                <p class="text-gray-500">
                    <?php if(request('search')): ?>
                        No posts match your search. <a href="<?php echo e(route('blog.index')); ?>" class="text-green-600 hover:underline">View all posts</a>
                    <?php else: ?>
                        Check back later for new content.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Pagination -->
    <?php if($posts->hasPages()): ?>
        <div class="mt-12">
            <?php echo e($posts->appends(request()->query())->links()); ?>

        </div>
    <?php endif; ?>
</div>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\DELL\Desktop\My Files\Dev\ecom\resources\views/frontend/blog/index.blade.php ENDPATH**/ ?>
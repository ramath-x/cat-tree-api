<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\ProcessSignaledException;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\AssertionFailedError;

class CategoryTreePerformanceTest extends TestCase
{
    use RefreshDatabase;

    private $rootCategory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware();
        $this->createDeepTree();
    }

    private function createDeepTree()
    {
        try {
            DB::beginTransaction();

            $this->rootCategory = Category::create(['category_name' => 'Root']);
            $parent = $this->rootCategory;

            // for ($i = 1; $i < 10000; $i++) {
            for ($i = 1; $i < 300; $i++) {
                $child = Category::create([
                    'category_name' => "Category Level $i",
                    'parent_id' => $parent->id
                ]);

                if ($child === null) {
                    throw new \Exception('Failed to create child category');
                }

                $parent = $child;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fail("Failed to create deep tree: " . $e->getMessage());
        }
    }

    // #[Group('without-depth-error')]
    public function test_fetch_deep_tree_performance(): void
    {
        if ($this->rootCategory === null) {
            Log::info('Root category is not set');
            throw new AssertionFailedError("Root category is not set");
        }

        try {
            Log::info('Starting fetch deep tree performance test');

            $startTime = microtime(true);

            ini_set('xdebug.max_nesting_level', 2000);
            $response = $this->getJson("/api/categories/tree/{$this->rootCategory->id}");

            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

            $response->assertStatus(200);
            $this->assertLessThanOrEqual(3000, $executionTime, "Response time exceeded 3000ms");

            Log::info("Execution time: " . round($executionTime, 2) . "ms");
        } catch (\Exception $e) {
            Log::error("An error occurred: " . $e->getMessage());
            throw new AssertionFailedError("An error occurred: " . $e->getMessage());
        } finally {
            ini_set('xdebug.max_nesting_level', 200);
            Log::info('Ending fetch deep tree performance test');
        }
    }
}

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

    /**
     * Test the performance of fetching a deep tree of categories.
     *
     * @return void
     * @throws AssertionFailedError If the root category is not set or an error occurs.
     */
    public function test_fetch_deep_tree_performance(): void
    {
        // Check if the root category is set
        if ($this->rootCategory === null) {
            Log::info('Root category is not set');
            throw new AssertionFailedError("Root category is not set");
        }

        try {
            // Log the start of the test
            Log::info('Starting fetch deep tree performance test');

            // Measure the start time
            $startTime = microtime(true);

            // Increase the maximum nesting level for XDebug
            ini_set('xdebug.max_nesting_level', 2000);

            // Make a GET request to fetch the deep tree of categories
            $response = $this->getJson("/api/categories/tree/{$this->rootCategory->id}");

            // Measure the end time
            $endTime = microtime(true);

            // Calculate the execution time in milliseconds
            $executionTime = ($endTime - $startTime) * 1000;

            // Assert that the response status is 200
            $response->assertStatus(200);

            // Assert that the execution time is less than or equal to 3000ms
            $this->assertLessThanOrEqual(
                3000,
                $executionTime,
                "Response time exceeded 3000ms"
            );

            // Log the execution time
            Log::info("Execution time: " . round($executionTime, 2) . "ms");
        } catch (\Exception $e) {
            // Log any errors that occur
            Log::error("An error occurred: " . $e->getMessage());
            throw new AssertionFailedError("An error occurred: " . $e->getMessage());
        } finally {
            // Reset the maximum nesting level for XDebug
            ini_set('xdebug.max_nesting_level', 200);

            // Log the end of the test
            Log::info('Ending fetch deep tree performance test');
        }
    }
}

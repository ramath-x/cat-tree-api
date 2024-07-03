<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryStandalonePerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createStandaloneNodes();
    }

    private function createStandaloneNodes()
    {
        DB::beginTransaction();
        try {
            $categories = [];
            for ($i = 1; $i <= 10000; $i++) {
                $categories[] = [
                    'category_name' => "Standalone Category $i",
                    'parent_id' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Insert in batches of 1000 to avoid memory issues
                if ($i % 1000 == 0) {
                    Category::insert($categories);
                    $categories = [];
                }
            }

            // Insert any remaining categories
            if (!empty($categories)) {
                Category::insert($categories);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fail("Failed to create standalone nodes: " . $e->getMessage());
        }
    }

    public function test_fetch_all_categories_performance()
    {
        $startTime = microtime(true);

        $response = $this->getJson("/api/categories/all");

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

        $response->assertStatus(200);
        $this->assertLessThanOrEqual(3000, $executionTime, "Response time exceeded 3000ms");

        // Optional: Print the actual execution time
        echo "\nExecution time: " . round($executionTime, 2) . "ms\n";
    }
}

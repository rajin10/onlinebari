<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Category;
use App\Models\miniCategory;
use App\Models\Page;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $this->configureModels();

        Builder::macro('filter', function ($key, $column = null, $compareWith = null, $filterIf = true) {
            if (($value = request($key, null)) !== null && $filterIf) {
                return $this->where($column ?? $key, $compareWith ?? '=', $value);
            }

            return $this;
        });

        Builder::macro('filterWith', function ($key, $column = null) {
            if ((request($key, null)) !== null) {
                $value = request($key, null);

                return $this->whereIn($column ?? $key, $value);
            }

            return $this;
        });

        Builder::macro('whereLike', function ($attributes, ?string $searchTerm) {
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                foreach ($attributes as $attribute) {
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            [$relationName, $relationAttribute] = explode('.', $attribute);

                            $query->orWhereHas($relationName, function (Builder $query) use ($relationAttribute, $searchTerm) {
                                $query->where($relationAttribute, 'LIKE', "%{$searchTerm}%");
                            });
                        },
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });

            return $this;
        });

        try {
            $footerPages = Page::where('status', '1')->where('position', '1')->get();
            $categories_f = Category::where('status', true)->orderBy('updated_at', 'desc')->where('is_feature', '1')->take(14)->get();

            // Updated by Hridoy
            $categories = Category::where('status', true)->oldest('id')->get(['name', 'slug', 'cover_photo']);

            $sub_f = SubCategory::where('status', true)->orderBy('updated_at', 'desc')->where('is_feature', '1')->take(10)->get();
            $mini_f = miniCategory::where('status', true)->orderBy('updated_at', 'desc')->where('is_feature', '1')->take(10)->get();

            View::share(
                [
                    'footerPages' => $footerPages,
                    'categories_f' => $categories_f,
                    'categories' => $categories,
                    'sub_f' => $sub_f,
                    'mini_f' => $mini_f,
                ]
            );
        } catch (\Exception $e) {
            // DB not available (artisan commands, empty schema, connection issue):
            // share empty defaults so views never hit an undefined variable.
            View::share([
                'footerPages' => collect(),
                'categories_f' => collect(),
                'categories' => collect(),
                'sub_f' => collect(),
                'mini_f' => collect(),
            ]);
        }
    }

    /**
     * Configure the application models
     */
    private function configureModels(): void
    {
        // Model::shouldBeStrict(app()->isLocal());
        // Model::automaticallyEagerLoadRelationships();
    }
}

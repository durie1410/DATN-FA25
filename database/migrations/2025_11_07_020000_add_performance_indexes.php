<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add indexes to books table
        Schema::table('books', function (Blueprint $table) {
            // Index for category_id (frequently used in queries)
            if (!$this->hasIndex('books', 'books_category_id_index')) {
                $table->index('category_id', 'books_category_id_index');
            }
            
            // Index for nha_xuat_ban_id (frequently used in queries)
            if (!$this->hasIndex('books', 'books_nha_xuat_ban_id_index')) {
                $table->index('nha_xuat_ban_id', 'books_nha_xuat_ban_id_index');
            }
            
            // Index for trang_thai (frequently used in filtering)
            if (!$this->hasIndex('books', 'books_trang_thai_index')) {
                $table->index('trang_thai', 'books_trang_thai_index');
            }
            
            // Composite index for active books with category
            if (!$this->hasIndex('books', 'books_trang_thai_category_id_index')) {
                $table->index(['trang_thai', 'category_id'], 'books_trang_thai_category_id_index');
            }
            
            // Index for is_featured (used in homepage queries)
            if (!$this->hasIndex('books', 'books_is_featured_index')) {
                $table->index('is_featured', 'books_is_featured_index');
            }
            
            // Index for created_at (used in sorting)
            if (!$this->hasIndex('books', 'books_created_at_index')) {
                $table->index('created_at', 'books_created_at_index');
            }
        });

        // Add indexes to readers table
        if (Schema::hasTable('readers')) {
            Schema::table('readers', function (Blueprint $table) {
                // Index for email (frequently used in lookups)
                if (!$this->hasIndex('readers', 'readers_email_index')) {
                    $table->index('email', 'readers_email_index');
                }
                
                // Index for so_the_doc_gia (frequently used in lookups)
                if (!$this->hasIndex('readers', 'readers_so_the_doc_gia_index')) {
                    $table->index('so_the_doc_gia', 'readers_so_the_doc_gia_index');
                }
                
                // Index for trang_thai (frequently used in filtering)
                if (!$this->hasIndex('readers', 'readers_trang_thai_index')) {
                    $table->index('trang_thai', 'readers_trang_thai_index');
                }
                
                // Index for user_id (if exists)
                if (Schema::hasColumn('readers', 'user_id') && !$this->hasIndex('readers', 'readers_user_id_index')) {
                    $table->index('user_id', 'readers_user_id_index');
                }
                
                // Index for faculty_id (if exists)
                if (Schema::hasColumn('readers', 'faculty_id') && !$this->hasIndex('readers', 'readers_faculty_id_index')) {
                    $table->index('faculty_id', 'readers_faculty_id_index');
                }
                
                // Index for department_id (if exists)
                if (Schema::hasColumn('readers', 'department_id') && !$this->hasIndex('readers', 'readers_department_id_index')) {
                    $table->index('department_id', 'readers_department_id_index');
                }
            });
        }

        // Add indexes to borrows table
        if (Schema::hasTable('borrows')) {
            Schema::table('borrows', function (Blueprint $table) {
                // Index for reader_id (frequently used in queries)
                if (!$this->hasIndex('borrows', 'borrows_reader_id_index')) {
                    $table->index('reader_id', 'borrows_reader_id_index');
                }
                
                // Index for book_id (frequently used in queries)
                if (!$this->hasIndex('borrows', 'borrows_book_id_index')) {
                    $table->index('book_id', 'borrows_book_id_index');
                }
                
                // Index for trang_thai (frequently used in filtering)
                if (!$this->hasIndex('borrows', 'borrows_trang_thai_index')) {
                    $table->index('trang_thai', 'borrows_trang_thai_index');
                }
                
                // Composite index for active borrows with due date
                if (!$this->hasIndex('borrows', 'borrows_trang_thai_ngay_hen_tra_index')) {
                    $table->index(['trang_thai', 'ngay_hen_tra'], 'borrows_trang_thai_ngay_hen_tra_index');
                }
                
                // Index for ngay_hen_tra (used in overdue queries)
                if (!$this->hasIndex('borrows', 'borrows_ngay_hen_tra_index')) {
                    $table->index('ngay_hen_tra', 'borrows_ngay_hen_tra_index');
                }
                
                // Index for created_at (used in sorting)
                if (!$this->hasIndex('borrows', 'borrows_created_at_index')) {
                    $table->index('created_at', 'borrows_created_at_index');
                }
            });
        }

        // Add indexes to categories table
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                // Index for trang_thai (frequently used in filtering)
                if (!$this->hasIndex('categories', 'categories_trang_thai_index')) {
                    $table->index('trang_thai', 'categories_trang_thai_index');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove indexes from books table
        Schema::table('books', function (Blueprint $table) {
            $table->dropIndex('books_category_id_index');
            $table->dropIndex('books_nha_xuat_ban_id_index');
            $table->dropIndex('books_trang_thai_index');
            $table->dropIndex('books_trang_thai_category_id_index');
            $table->dropIndex('books_is_featured_index');
            $table->dropIndex('books_created_at_index');
        });

        // Remove indexes from readers table
        if (Schema::hasTable('readers')) {
            Schema::table('readers', function (Blueprint $table) {
                $table->dropIndex('readers_email_index');
                $table->dropIndex('readers_so_the_doc_gia_index');
                $table->dropIndex('readers_trang_thai_index');
                if (Schema::hasColumn('readers', 'user_id')) {
                    $table->dropIndex('readers_user_id_index');
                }
                if (Schema::hasColumn('readers', 'faculty_id')) {
                    $table->dropIndex('readers_faculty_id_index');
                }
                if (Schema::hasColumn('readers', 'department_id')) {
                    $table->dropIndex('readers_department_id_index');
                }
            });
        }

        // Remove indexes from borrows table
        if (Schema::hasTable('borrows')) {
            Schema::table('borrows', function (Blueprint $table) {
                $table->dropIndex('borrows_reader_id_index');
                $table->dropIndex('borrows_book_id_index');
                $table->dropIndex('borrows_trang_thai_index');
                $table->dropIndex('borrows_trang_thai_ngay_hen_tra_index');
                $table->dropIndex('borrows_ngay_hen_tra_index');
                $table->dropIndex('borrows_created_at_index');
            });
        }

        // Remove indexes from categories table
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropIndex('categories_trang_thai_index');
            });
        }
    }

    /**
     * Check if index exists
     */
    private function hasIndex($table, $indexName)
    {
        $connection = Schema::getConnection();
        $database = $connection->getDatabaseName();
        
        $result = $connection->select(
            "SELECT COUNT(*) as count FROM information_schema.statistics 
             WHERE table_schema = ? AND table_name = ? AND index_name = ?",
            [$database, $table, $indexName]
        );
        
        return $result[0]->count > 0;
    }
}





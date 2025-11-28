public function up(): void
{
Schema::create('movies', function (Blueprint $table) {
$table->id();
$table->string('title');
$table->string('poster')->nullable();
$table->text('description')->nullable();
$table->string('category')->nullable();
$table->integer('duration');
$table->date('release_date')->nullable();
$table->timestamps();
});
}
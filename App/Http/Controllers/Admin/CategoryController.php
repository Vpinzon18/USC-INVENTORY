<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Muestra el listado de categorías con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $categories = Category::query()
            ->when($search, function($query, $search) {
                $query->where('name', 'ilike', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.categories.index', compact('categories', 'search'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Almacena una nueva categoría validando que no se duplique.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ], [
            'name.unique' => 'Esta categoría ya se encuentra registrada en el sistema.',
            'name.required' => 'El nombre de la categoría es obligatorio.'
        ]);

        // Guardamos transformando a mayúsculas para estandarizar el inventario
        Category::create([
            'name' => strtoupper($request->input('name'))
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría registrada exitosamente.');
    }

    /**
     * Muestra el formulario de edición.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Actualiza la categoría validando exclusión de ID.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ], [
            'name.unique' => 'Este nombre de categoría ya está siendo usado.'
        ]);

        $category->update([
            'name' => strtoupper($request->input('name'))
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Elimina la categoría asegurando que no rompa la integridad física.
     */
    public function destroy(Category $category)
    {
        // Validación de seguridad de llave foránea antes de eliminar
        if ($category->assets()->exists()) {
            return redirect()->route('categories.index')
                ->with('error', 'No se puede eliminar la categoría porque existen equipos asociados a ella en el inventario.');
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Categoría eliminada del sistema.');
    }
}
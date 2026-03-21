package com.dk.assistant.memory

import android.content.Context
import androidx.room.*
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch

@Entity(tableName = "interactions")
data class Interaction(
    @PrimaryKey(autoGenerate = true) val id: Long = 0,
    val sender: String,
    val message: String,
    val timestamp: Long = System.currentTimeMillis()
)

@Entity(tableName = "knowledge")
data class KnowledgeEntry(
    @PrimaryKey(autoGenerate = true) val id: Long = 0,
    val key: String,
    val value: String,
    val timestamp: Long = System.currentTimeMillis()
)

@Dao
interface InteractionDao {
    @Insert suspend fun insert(i: Interaction)
    @Query("SELECT * FROM interactions ORDER BY timestamp DESC LIMIT :limit") suspend fun getRecent(limit: Int = 100): List<Interaction>
    @Query("DELETE FROM interactions") suspend fun clear()
}

@Dao
interface KnowledgeDao {
    @Insert(onConflict = OnConflictStrategy.REPLACE) suspend fun insert(e: KnowledgeEntry)
    @Query("SELECT * FROM knowledge WHERE \`key\` = :key LIMIT 1") suspend fun getByKey(key: String): KnowledgeEntry?
    @Query("SELECT * FROM knowledge WHERE \`key\` LIKE '%'||:q||'%' OR value LIKE '%'||:q||'%'") suspend fun search(q: String): List<KnowledgeEntry>
    @Query("SELECT * FROM knowledge ORDER BY timestamp DESC") suspend fun getAll(): List<KnowledgeEntry>
    @Query("DELETE FROM knowledge") suspend fun clear()
}

@Database(entities = [Interaction::class, KnowledgeEntry::class], version = 1, exportSchema = false)
abstract class DKDatabase : RoomDatabase() {
    abstract fun interactionDao(): InteractionDao
    abstract fun knowledgeDao(): KnowledgeDao
    companion object {
        @Volatile private var INSTANCE: DKDatabase? = null
        fun getInstance(ctx: Context): DKDatabase = INSTANCE ?: synchronized(this) {
            Room.databaseBuilder(ctx.applicationContext, DKDatabase::class.java, "dk_db")
                .fallbackToDestructiveMigration().build().also { INSTANCE = it }
        }
    }
}

class MemoryManager(context: Context) {
    private val db = DKDatabase.getInstance(context)
    private val scope = CoroutineScope(Dispatchers.IO)

    fun logInteraction(sender: String, message: String) {
        scope.launch { db.interactionDao().insert(Interaction(sender = sender, message = message)) }
    }
    fun remember(key: String, value: String) {
        scope.launch { db.knowledgeDao().insert(KnowledgeEntry(key = key, value = value)) }
    }
    suspend fun recall(key: String): String? = db.knowledgeDao().getByKey(key)?.value
    suspend fun search(query: String): List<KnowledgeEntry> = db.knowledgeDao().search(query)
    fun clearAll() { scope.launch { db.interactionDao().clear(); db.knowledgeDao().clear() } }
    suspend fun getRecentContext(limit: Int = 20): String =
        db.interactionDao().getRecent(limit).reversed().joinToString("\n") { "${it.sender}: ${it.message}" }
}
